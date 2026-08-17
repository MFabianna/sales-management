@extends('layouts.sales')

@section('titre', 'Modifier le client')

@section('content')
    <h1 class="section-title">Modifier : {{ $client->prenom }} {{ $client->nom }}</h1>

    <div class="card p-4" style="max-width: 700px;">
        {{-- Le formulaire HTML n'accepte que GET et POST.
             @method('PUT') ajoute un champ cache qui dit a Laravel : "c'est une mise a jour" --}}
        <form method="POST" action="{{ route('clients.update', $client) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom', $client->nom) }}" required
                           class="form-control @error('nom') is-invalid @enderror">
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prenom *</label>
                    <input type="text" name="prenom" value="{{ old('prenom', $client->prenom) }}" required
                           class="form-control @error('prenom') is-invalid @enderror">
                    @error('prenom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Contact</label>
                <input type="text" name="contact" value="{{ old('contact', $client->contact) }}"
                       class="form-control @error('contact') is-invalid @enderror">
                @error('contact')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse</label>
                <textarea name="adresse" rows="3"
                          class="form-control @error('adresse') is-invalid @enderror">{{ old('adresse', $client->adresse) }}</textarea>
                @error('adresse')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-rose">Mettre a jour</button>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-rose">Annuler</a>
            </div>
        </form>
    </div>
@endsection