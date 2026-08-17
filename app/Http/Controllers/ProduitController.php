<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    // GET /produits : liste avec filtres categorie et disponibilite
    public function index(Request $request)
    {
        $categorieId = $request->input('categorie_id');
        $disponibilite = $request->input('disponibilite');

        $produits = Produit::with('categorie')   // charge la categorie de chaque produit
            ->when($categorieId, function ($query) use ($categorieId) {
                $query->where('categorie_id', $categorieId);
            })
            ->when($disponibilite === 'en_stock', function ($query) {
                $query->where('quantite_stock', '>', 0);
            })
            ->when($disponibilite === 'rupture', function ($query) {
                $query->where('quantite_stock', '<=', 0);
            })
            ->latest()
            ->paginate(10);

        $categories = Categorie::orderBy('nom')->get();

        return view('produits.index', compact('produits', 'categories', 'categorieId', 'disponibilite'));
    }

    // GET /produits/create : formulaire d'ajout
    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('produits.create', compact('categories'));
    }

    // POST /produits : validation + generation du code + enregistrement
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'quantite_stock' => 'required|numeric|min:0',
            'unite' => 'required|in:kg,litre,unite,nombre',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        // Le code est genere par le serveur, JAMAIS par l'utilisateur
        $validated['code'] = Produit::genererCode();

        Produit::create($validated);

        return redirect()->route('produits.index')
            ->with('success', 'Produit ajoute avec succes. Code genere : ' . $validated['code']);
    }

    // GET /produits/{produit}/edit : formulaire de modification
    public function edit(Produit $produit)
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('produits.edit', compact('produit', 'categories'));
    }

    // PUT /produits/{produit} : mise a jour (le code ne change jamais !)
    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'quantite_stock' => 'required|numeric|min:0',
            'unite' => 'required|in:kg,litre,unite,nombre',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        $produit->update($validated);

        return redirect()->route('produits.index')
            ->with('success', 'Produit modifie avec succes.');
    }

    // DELETE /produits/{produit}
    public function destroy(Produit $produit)
    {
        $produit->delete();

        return redirect()->route('produits.index')
            ->with('success', 'Produit supprime avec succes.');
    }
}