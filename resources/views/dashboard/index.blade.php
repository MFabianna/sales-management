@extends('layouts.sales')

@section('titre', 'Tableau de bord')

@section('content')
    <h1 class="section-title mb-4">
        Tableau de bord - {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('MMMM YYYY') }}
    </h1>

    {{-- 1. CARTES DE STATISTIQUES --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-4 h-100" style="border-left: 6px solid var(--rouge);">
                <div class="text-muted small">Ventes ce mois-ci</div>
                <div class="fs-2 fw-bold" style="color: var(--rouge);">{{ $ventesCeMois }}</div>
                <div class="text-muted small mt-2">
                    Chiffre d'affaires : {{ number_format($chiffreAffairesMois, 2, ',', ' ') }} Ar
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 h-100" style="border-left: 6px solid var(--rose);">
                <div class="text-muted small">Client le plus fidele</div>
                <div class="fs-4 fw-bold" style="color: var(--marron);">
                    @if($topClients->isNotEmpty())
                        {{ $topClients->first()->prenom }} {{ $topClients->first()->nom }}
                    @else
                        Aucun
                    @endif
                </div>
                <div class="text-muted small mt-2">
                    @if($topClients->isNotEmpty())
                        {{ $topClients->first()->ventes_count }} achats enregistres
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 h-100" style="border-left: 6px solid var(--marron);">
                <div class="text-muted small">Alertes Stock Faible</div>
                <div class="fs-2 fw-bold text-danger">{{ $alertesStock->count() }}</div>
                <div class="text-muted small mt-2">Produits a reapprovisionner</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- 2. GRAPHIQUE DES VENTES (6 derniers mois) --}}
        <div class="col-md-7">
            <div class="card p-4 h-100">
                <h5 class="mb-3" style="color: var(--rouge);">Evolution des ventes (6 derniers mois)</h5>
                <div class="d-flex align-items-end justify-content-around"
                     style="height: 200px; border-bottom: 2px solid var(--marron); padding-bottom: 5px;">
                    @foreach($graphiqueData as $data)
                        @php
                            $hauteur = ($data['ventes'] / $maxVentes) * 100;
                            if ($hauteur < 5 && $data['ventes'] > 0) $hauteur = 5;
                        @endphp
                        <div class="text-center" style="flex: 1;">
                            <div class="fw-bold small" style="color: var(--rouge);">{{ $data['ventes'] }}</div>
                            <div class="mx-auto rounded-top"
                                 style="width: 30px; height: {{ $hauteur }}%; background: linear-gradient(180deg, var(--rose), var(--rouge)); transition: height 0.5s;">
                            </div>
                            <div class="small text-muted mt-1">{{ ucfirst($data['mois']) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 3. TOP 5 CLIENTS --}}
        <div class="col-md-5">
            <div class="card p-4 h-100">
                <h5 class="mb-3" style="color: var(--rouge);">Top 5 des clients</h5>
                @if($topClients->isNotEmpty())
                    <table class="table table-sm align-middle mb-0">
                        <tbody>
                            @foreach($topClients as $index => $client)
                                <tr>
                                    <td class="fw-bold" style="color: var(--marron);">#{{ $index + 1 }}</td>
                                    <td>{{ $client->prenom }} {{ $client->nom }}</td>
                                    <td class="text-end">
                                        <span class="badge rounded-pill"
                                              style="background-color: var(--rose); color: var(--rouge);">
                                            {{ $client->ventes_count }} achats
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted mb-0">Aucune vente enregistree pour le moment.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- 4. TOP 1 PAR CATEGORIE --}}
    <div class="card p-4 mb-4">
        <h5 class="mb-3" style="color: var(--rouge);">Produits champions par categorie</h5>
        @if($topsParCategorie->isNotEmpty())
            <div class="row g-3">
                @foreach($topsParCategorie as $categorie)
                    @php $produitChampion = $categorie->produits->first(); @endphp
                    <div class="col-md-4 col-lg-3">
                        <div class="p-3 rounded-3 h-100"
                             style="background-color: var(--rose-clair); border: 1px solid var(--rose);">
                            <div class="text-muted small text-uppercase fw-bold">{{ $categorie->nom }}</div>
                            <div class="fs-5 fw-bold" style="color: var(--rouge);">{{ $produitChampion->nom }}</div>
                            <div class="mt-2">
                                <span class="badge" style="background-color: var(--marron);">
                                    {{ $produitChampion->ventes_sum_quantite }} {{ $produitChampion->unite }}(s) vendus
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0">Pas encore assez de donnees pour determiner les champions.</p>
        @endif
    </div>

    {{-- 5. ALERTES STOCK FAIBLE --}}
    @if($alertesStock->isNotEmpty())
        <div class="card p-4 border-danger">
            <h5 class="text-danger mb-3">Alertes de reapprovisionnement</h5>
            <ul class="list-group list-group-flush">
                @foreach($alertesStock as $produit)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>
                            <strong>{{ $produit->nom }}</strong>
                            <small class="text-muted">({{ $produit->categorie->nom ?? 'Sans categorie' }})</small>
                        </span>
                        <span class="badge bg-danger rounded-pill">
                            Reste {{ $produit->quantite_stock }} {{ $produit->unite }}(s)
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection