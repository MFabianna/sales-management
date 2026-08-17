<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Produit;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    // GET /ventes
    public function index()
    {
        // On charge les relations pour eviter les requetes en boucle (N+1 problem)
        $ventes = Vente::with(['client', 'produit'])
            ->latest('date_vente')
            ->paginate(10);

        return view('ventes.index', compact('ventes'));
    }

    // GET /ventes/create
    public function create()
    {
        $clients = Client::orderBy('nom')->get();
        // On ne recupere que les produits qui ont du stock
        $produits = Produit::where('quantite_stock', '>', 0)->orderBy('nom')->get();
        
        return view('ventes.create', compact('clients', 'produits'));
    }

    // POST /ventes
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|numeric|min:0.01',
            'date_vente' => 'required|date',
        ]);

        $produit = Produit::findOrFail($validated['produit_id']);

        // 1. Verification du stock (Validation metier)
        if ($produit->quantite_stock < $validated['quantite']) {
            return back()->withInput()->withErrors([
                'quantite' => 'Stock insuffisant ! Il reste seulement ' . $produit->quantite_stock . ' ' . $produit->unite . '(s) en stock.'
            ]);
        }

        // 2. Transaction pour garantir l'integrite des donnees
        DB::transaction(function () use ($validated, $produit) {
            // Calcul du montant cote serveur (securite : on ne fait jamais confiance au calcul du navigateur)
            $montant = $validated['quantite'] * $produit->prix;
            
            // Generation du code automatique
            $code = Vente::genererCode();

            // Creation de la vente
            Vente::create([
                'code' => $code,
                'date_vente' => $validated['date_vente'],
                'montant' => $montant,
                'quantite' => $validated['quantite'],
                'client_id' => $validated['client_id'],
                'produit_id' => $validated['produit_id'],
            ]);

            // Decrementation du stock
            $produit->quantite_stock -= $validated['quantite'];
            $produit->save();
        });

        return redirect()->route('ventes.index')
            ->with('success', 'Vente enregistree avec succes et stock mis a jour.');
    }
    
   // GET /ventes/{vente}/edit
public function edit(Vente $vente)
{
    $clients = Client::orderBy('nom')->get();
    // Pas de filtre sur le stock ici : le produit actuel doit rester
    // visible dans la liste meme s'il est en rupture
    $produits = Produit::orderBy('nom')->get();

    return view('ventes.edit', compact('vente', 'clients', 'produits'));
}

// PUT /ventes/{vente}
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

    // Calcul du stock disponible : si c'est le meme produit,
    // on remet d'abord l'ancienne quantite dans la balance
    $stockDisponible = $ancienProduit->id === $nouveauProduit->id
        ? $ancienProduit->quantite_stock + $vente->quantite
        : $nouveauProduit->quantite_stock;

    if ($stockDisponible < $validated['quantite']) {
        return back()->withInput()->withErrors([
            'quantite' => 'Stock insuffisant pour cette modification.'
        ]);
    }

    DB::transaction(function () use ($validated, $vente, $nouveauProduit, $ancienProduit) {
        // 1. Rendre l'ancienne quantite au stock
        $ancienProduit->increment('quantite_stock', $vente->quantite);

        // 2. Retirer la nouvelle quantite du stock
        $nouveauProduit->decrement('quantite_stock', $validated['quantite']);

        // 3. Mettre a jour la vente (le code ne change JAMAIS)
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

    // DELETE /ventes/{vente}
    public function destroy(Vente $vente)
    {
        DB::transaction(function () use ($vente) {
            // Remettre la quantite en stock AVANT de supprimer
            $vente->produit->increment('quantite_stock', $vente->quantite);
            $vente->delete();
        });

        return redirect()->route('ventes.index')
            ->with('success', 'Vente supprimee et stock restaure.');
    }
}