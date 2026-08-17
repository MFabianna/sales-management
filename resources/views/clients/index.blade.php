@extends('layouts.sales')

@section('titre', 'Clients')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="section-title mb-0">Liste des clients</h1>
        <a href="{{ route('clients.create') }}" class="btn btn-rose">+ Nouveau client</a>
    </div>

    {{-- Barre de recherche --}}
    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('clients.index') }}">
            <div class="input-group">
                <input type="text" name="recherche" class="form-control"
                       placeholder="Rechercher par nom, prenom ou contact..."
                       value="{{ $recherche }}">
                <button type="submit" class="btn btn-rose">Rechercher</button>
                @if($recherche)
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-rose">Effacer</a>
                @endif
            </div>
        </form>
    </div>

    <div class="card p-3">
        @if($clients->count() > 0)
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Contact</th>
                        <th>Adresse</th>
                        <th>Achats</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>{{ $client->nom }}</td>
                            <td>{{ $client->prenom }}</td>
                            <td>{{ $client->contact ?? '-' }}</td>
                            <td>{{ $client->adresse ?? '-' }}</td>
                            <td>
                                <span class="badge rounded-pill"
                                      style="background-color: var(--rose); color: var(--rouge);">
                                    {{ $client->ventes_count }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('clients.show', $client) }}"
                                   class="btn btn-sm btn-outline-rose">Voir</a>
                                <a href="{{ route('clients.edit', $client) }}"
                                   class="btn btn-sm btn-outline-rose">Modifier</a>
                                <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Supprimer ce client ? Ses ventes seront aussi supprimees.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination avec le style Bootstrap 5 --}}
            {{ $clients->withQueryString()->links('pagination::bootstrap-5') }}
        @else
            <p class="text-center text-muted mb-0">Aucun client trouve.</p>
        @endif
    </div>
@endsection