@extends('layouts.sales')

@section('titre', 'Details du client')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="section-title mb-0">Details du client</h1>
        <a href="{{ route('clients.index') }}" class="btn btn-outline-rose">Retour a la liste</a>
    </div>

    <div class="row g-3">
        {{-- Carte d'identite du client --}}
        <div class="col-md-4">
            <div class="card p-4">
                <h4>{{ $client->prenom }} {{ $client->nom }}</h4>
                <p class="mb-1"><strong>Contact :</strong> {{ $client->contact ?? 'Non renseigne' }}</p>
                <p class="mb-1"><strong>Adresse :</strong> {{ $client->adresse ?? 'Non renseignee' }}</p>
                <p class="mb-0">
                    <strong>Client depuis :</strong>
                    {{ $client->created_at->format('d/m/Y') }}
                </p>
                <div class="mt-3">
                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-rose">Modifier</a>
                </div>
            </div>
        </div>

        {{-- Historique des achats (exigence du cahier des charges) --}}
        <div class="col-md-8">
            <div class="card p-4">
                <h4>Historique des achats ({{ $client->ventes->count() }})</h4>
                @if($client->ventes->count() > 0)
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Date</th>
                                <th>Produit</th>
                                <th>Quantite</th>
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($client->ventes as $vente)
                                <tr>
                                    <td><code>{{ $vente->code }}</code></td>
                                    <td>{{ $vente->date_vente->format('d/m/Y') }}</td>
                                    <td>{{ $vente->produit->nom }}</td>
                                    <td>{{ $vente->quantite }}</td>
                                    <td>{{ number_format($vente->montant, 2) }} Ar</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted mb-0">Ce client n'a pas encore effectue d'achat.</p>
                @endif
            </div>
        </div>
    </div>
@endsection