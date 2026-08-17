<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Produit;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    // GET /ventes : liste des ventes avec pagination
    public function index()
    {
        $ventes = Vente::with(['client', 'produit'])
            ->latest('date_vente')
            ->paginate(10);

        return view('ventes.index', compact('ventes'));
    }

    // GET /ventes/create : formulaire de creation
    public function create()
    {
        $clients = Client::orderBy('nom')->get();

        // On n'affiche QUE les produits qui ont du stock disponible
        $produits = Produit::where('quantite_stock', '>', 0)
                           ->orderBy('nom')
                           ->get();

        return view('ventes.create', compact('clients', 'produits'));
    }

    // POST /ventes : validation + creation + decrement du stock
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|numeric|min:0.01',
            'date_vente' => 'required|date',
        ]);

        $produit = Produit::findOrFail($validated['produit_id']);

        // Verification du stock
        if ($produit->quantite_stock < $validated['quantite']) {
            return back()->withInput()->withErrors([
                'quantite' => 'Stock insuffisant ! Il reste seulement ' . $produit->quantite_stock . ' ' . $produit->unite . '(s) en stock.'
            ]);
        }

        // Transaction pour garantir l'integrite des donnees
        DB::transaction(function () use ($validated, $produit) {
            $montant = $validated['quantite'] * $produit->prix;
            $code = Vente::genererCode();

            Vente::create([
                'code' => $code,
                'date_vente' => $validated['date_vente'],
                'montant' => $montant,
                'quantite' => $validated['quantite'],
                'client_id' => $validated['client_id'],
                'produit_id' => $validated['produit_id'],
            ]);

            $produit->quantite_stock -= $validated['quantite'];
            $produit->save();
        });

        return redirect()->route('ventes.index')
            ->with('success', 'Vente enregistree avec succes et stock mis a jour.');
    }

    // GET /ventes/{vente}/edit : formulaire de modification
    public function edit(Vente $vente)
    {
        $clients = Client::orderBy('nom')->get();

        // On affiche TOUS les produits (pour que le produit actuel reste visible)
        $produits = Produit::orderBy('nom')->get();

        return view('ventes.edit', compact('vente', 'clients', 'produits'));
    }

    // PUT /ventes/{vente} : mise a jour + ajustement du stock
    public function update(Request $request, Vente $vente)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|numeric|min:0.01',
            'date_vente' => 'required|date',
        ]);

        $nouveauProduit = Produit::findOrFail($validated['produit_id']);
        $ancienProduit = $vente->produit;

        // Calcul du stock disponible
        $stockDisponible = $ancienProduit->id === $nouveauProduit->id
            ? $ancienProduit->quantite_stock + $vente->quantite
            : $nouveauProduit->quantite_stock;

        if ($stockDisponible < $validated['quantite']) {
            return back()->withInput()->withErrors([
                'quantite' => 'Stock insuffisant pour cette modification.'
            ]);
        }

        DB::transaction(function () use ($validated, $vente, $nouveauProduit, $ancienProduit) {
            // Rendre l'ancienne quantite au stock
            $ancienProduit->increment('quantite_stock', $vente->quantite);

            // Retirer la nouvelle quantite du stock
            $nouveauProduit->decrement('quantite_stock', $validated['quantite']);

            // Mettre a jour la vente (le code ne change jamais)
            $vente->update([
                'date_vente' => $validated['date_vente'],
                'quantite' => $validated['quantite'],
                'montant' => $validated['quantite'] * $nouveauProduit->prix,
                'client_id' => $validated['client_id'],
                'produit_id' => $nouveauProduit->id,
            ]);
        });

        return redirect()->route('ventes.index')
            ->with('success', 'Vente modifiee et stock ajuste.');
    }

    // DELETE /ventes/{vente} : suppression + restauration du stock
    public function destroy(Vente $vente)
    {
        DB::transaction(function () use ($vente) {
            // Remettre la quantite en stock avant de supprimer
            $vente->produit->increment('quantite_stock', $vente->quantite);
            $vente->delete();
        });

        return redirect()->route('ventes.index')
            ->with('success', 'Vente supprimee et stock restaure.');
    }
}