<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // GET /clients : liste avec recherche et pagination
    public function index(Request $request)
    {
        $recherche = $request->input('recherche');

        $clients = Client::query()
            ->when($recherche, function ($query) use ($recherche) {
                $query->where('nom', 'like', "%{$recherche}%")
                      ->orWhere('prenom', 'like', "%{$recherche}%")
                      ->orWhere('contact', 'like', "%{$recherche}%");
            })
            ->withCount('ventes')   // ajoute une colonne "ventes_count"
            ->latest()              // les plus recents d'abord
            ->paginate(10);         // 10 par page (pagination)

        return view('clients.index', compact('clients', 'recherche'));
    }

    // GET /clients/create : affiche le formulaire vide
    public function create()
    {
        return view('clients.create');
    }

    // POST /clients : valide puis enregistre
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'contact' => 'nullable|string|max:50',
            'adresse' => 'nullable|string|max:500',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client ajoute avec succes.');
    }

    // GET /clients/{client} : details + historique des achats
    public function show(Client $client)
    {
        // Charge les ventes ET leurs produits en une seule fois
        // (evite les requetes SQL repetees = "eager loading")
        $client->load('ventes.produit');

        return view('clients.show', compact('client'));
    }

    // GET /clients/{client}/edit : formulaire pre-rempli
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    // PUT /clients/{client} : valide puis met a jour
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'contact' => 'nullable|string|max:50',
            'adresse' => 'nullable|string|max:500',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client modifie avec succes.');
    }

    // DELETE /clients/{client} : suppression
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprime avec succes.');
    }
}