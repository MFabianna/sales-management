@extends('layouts.sales')

@section('titre', 'Factures')

@section('content')
    <h1 class="section-title">Inventaire des factures</h1>

    <div class="card p-3">
        @if($factures->count() > 0)
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Produit</th>
                        <th>Montant</th>
                        <th class="text-end">Telecharger</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($factures as $facture)
                        <tr>
                            <td><code class="text-danger fw-bold">{{ $facture->code }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($facture->date_vente)->format('d/m/Y') }}</td>
                            <td>{{ $facture->client->nom }} {{ $facture->client->prenom }}</td>
                            <td>{{ $facture->produit->nom }}</td>
                            <td class="fw-bold">{{ number_format($facture->montant, 2, ',', ' ') }} Ar</td>
                            <td class="text-end">
                                <a href="{{ route('factures.download', $facture) }}" class="btn btn-sm btn-rose">
                                    Telecharger PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $factures->links('pagination::bootstrap-5') }}
        @else
            <p class="text-center text-muted mb-0">
                Aucune facture pour le moment. Chaque vente generee automatiquement une facture.
            </p>
        @endif
    </div>
@endsection