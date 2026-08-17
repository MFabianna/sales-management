@extends('layouts.sales')

@section('titre', 'Nouveau client')

@section('content')
    <h1 class="section-title">Nouveau client</h1>

    <div class="card p-4" style="max-width: 700px;">
        <form method="POST" action="{{ route('clients.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                           class="form-control @error('nom') is-invalid @enderror">
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prenom *</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" required
                           class="form-control @error('prenom') is-invalid @enderror">
                    @error('prenom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Contact</label>
                <input type="text" name="contact" value="{{ old('contact') }}"
                       placeholder="Ex : 034 12 345 67"
                       class="form-control @error('contact') is-invalid @enderror">
                @error('contact')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse</label>
                <textarea name="adresse" rows="3"
                          class="form-control @error('adresse') is-invalid @enderror">{{ old('adresse') }}</textarea>
                @error('adresse')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-rose">Enregistrer</button>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-rose">Annuler</a>
            </div>
        </form>
    </div>
@endsection