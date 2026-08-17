@extends('layouts.sales')

@section('titre', 'Nouveau produit')

@section('content')
    <h1 class="section-title">Nouveau produit</h1>

    <div class="card p-4" style="max-width: 700px;">
        <div class="alert alert-info">
            Le code du produit sera genere automatiquement a l'enregistrement.
        </div>

        <form method="POST" action="{{ route('produits.store') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Nom du produit *</label>
                <input type="text" name="nom" value="{{ old('nom') }}" required
                       class="form-control @error('nom') is-invalid @enderror">
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="2"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prix (Ar) *</label>
                    <input type="number" step="0.01" min="0" name="prix" value="{{ old('prix') }}" required
                           class="form-control @error('prix') is-invalid @enderror">
                    @error('prix')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Quantite en stock *</label>
                    <input type="number" step="0.01" min="0" name="quantite_stock"
                           value="{{ old('quantite_stock') }}" required
                           class="form-control @error('quantite_stock') is-invalid @enderror">
                    @error('quantite_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Unite *</label>
                    <select name="unite" required
                            class="form-select @error('unite') is-invalid @enderror">
                        <option value="">Choisir une unite...</option>
                        <option value="kg" {{ old('unite') === 'kg' ? 'selected' : '' }}>Kg</option>
                        <option value="litre" {{ old('unite') === 'litre' ? 'selected' : '' }}>Litre</option>
                        <option value="unite" {{ old('unite') === 'unite' ? 'selected' : '' }}>Unite</option>
                        <option value="nombre" {{ old('unite') === 'nombre' ? 'selected' : '' }}>Nombre</option>
                    </select>
                    @error('unite')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Categorie *</label>
                    <select name="categorie_id" required
                            class="form-select @error('categorie_id') is-invalid @enderror">
                        <option value="">Choisir une categorie...</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}"
                                {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('categorie_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-rose">Enregistrer</button>
                <a href="{{ route('produits.index') }}" class="btn btn-outline-rose">Annuler</a>
            </div>
        </form>
    </div>
@endsection