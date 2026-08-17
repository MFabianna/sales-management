@extends('layouts.sales')

@section('titre', 'Produits')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="section-title mb-0">Inventaire des produits</h1>
        <a href="{{ route('produits.create') }}" class="btn btn-rose">+ Nouveau produit</a>
    </div>

    {{-- Filtres : categorie et disponibilite --}}
    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('produits.index') }}">
            <div class="row g-2">
                <div class="col-md-4">
                    <select name="categorie_id" class="form-select">
                        <option value="">Toutes les categories</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}"
                                {{ $categorieId == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="disponibilite" class="form-select">
                        <option value="">Toutes les disponibilites</option>
                        <option value="en_stock" {{ $disponibilite === 'en_stock' ? 'selected' : '' }}>En stock</option>
                        <option value="rupture" {{ $disponibilite === 'rupture' ? 'selected' : '' }}>Rupture de stock</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-rose">Filtrer</button>
                    <a href="{{ route('produits.index') }}" class="btn btn-outline-rose">Effacer</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card p-3">
        @if($produits->count() > 0)
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Unite</th>
                        <th>Categorie</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produits as $produit)
                        <tr>
                            <td><code>{{ $produit->code }}</code></td>
                            <td>{{ $produit->nom }}</td>
                            <td>{{ number_format($produit->prix, 2) }} Ar</td>
                            <td>
                                @if($produit->quantite_stock <= 0)
                                    <span class="badge" style="background-color: var(--marron);">Rupture</span>
                                @elseif($produit->estEnStockFaible())
                                    <span class="badge bg-danger">{{ $produit->quantite_stock }} (stock faible)</span>
                                @else
                                    <span class="badge rounded-pill"
                                          style="background-color: var(--rose); color: var(--rouge);">
                                        {{ $produit->quantite_stock }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ ucfirst($produit->unite) }}</td>
                            <td>{{ $produit->categorie->nom }}</td>
                            <td class="text-end">
                                <a href="{{ route('produits.edit', $produit) }}"
                                   class="btn btn-sm btn-outline-rose">Modifier</a>
                                <form action="{{ route('produits.destroy', $produit) }}" method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Supprimer ce produit ? Ses ventes seront aussi supprimees.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $produits->withQueryString()->links('pagination::bootstrap-5') }}
        @else
            <p class="text-center text-muted mb-0">Aucun produit trouve.</p>
        @endif
    </div>
@endsection