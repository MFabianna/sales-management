@extends('layouts.sales')

@section('titre', 'Ventes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="section-title mb-0">Historique des ventes</h1>
        <a href="{{ route('ventes.create') }}" class="btn btn-rose">+ Nouvelle vente</a>
    </div>

    <div class="card p-3">
        @if($ventes->count() > 0)
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Code Facture</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Produit</th>
                        <th>Quantite</th>
                        <th>Montant</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventes as $vente)
                        <tr>
                            <td><code class="text-danger fw-bold">{{ $vente->code }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</td>
                            <td>{{ $vente->client->nom }} {{ $vente->client->prenom }}</td>
                            <td>{{ $vente->produit->nom }}</td>
                            <td>{{ $vente->quantite }} {{ $vente->produit->unite }}</td>
                            <td class="fw-bold">{{ number_format($vente->montant, 2, ',', ' ') }} Ar</td>
                            <td class="text-end">
                                <a href="{{ route('ventes.edit', $vente) }}" class="btn btn-sm btn-outline-rose">Modifier</a>
                                <a href="{{ route('factures.download', $vente) }}" class="btn btn-sm btn-outline-rose">Facture</a>
                                <form action="{{ route('ventes.destroy', $vente) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Supprimer cette vente ? Le stock sera restaure.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $ventes->links('pagination::bootstrap-5') }}
        @else
            <p class="text-center text-muted mb-0">Aucune vente enregistree pour le moment.</p>
        @endif
    </div>
@endsection