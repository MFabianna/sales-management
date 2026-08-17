<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SALES - @yield('titre')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --rose: #F8BBD0;
            --rose-clair: #FCE4EC;
            --rouge: #8B1E3F;
            --rouge-fonce: #6D1732;
            --marron: #795548;
            --fond: #FFF0F5;
            --texte: #3E2723;
        }
        body { background-color: var(--fond); color: var(--texte); }
        h1, h2, h3, h4 { color: var(--rouge); }
        .navbar-sales {
            background: linear-gradient(90deg, var(--rouge), var(--rouge-fonce));
            box-shadow: 0 4px 12px rgba(139, 30, 63, 0.3);
        }
        .navbar-sales .navbar-brand { color: var(--fond); font-weight: bold; letter-spacing: 3px; }
        .navbar-sales .nav-link { color: var(--rose-clair); margin: 0 4px; border-radius: 8px; }
        .navbar-sales .nav-link:hover, .navbar-sales .nav-link.active {
            background-color: var(--rose);
            color: var(--rouge);
        }
        .card { border: none; border-radius: 18px; box-shadow: 0 6px 18px rgba(139, 30, 63, 0.12); }
        .table thead { background-color: var(--rouge); color: white; }
        .table tbody tr:hover { background-color: var(--rose-clair); }
        .btn-rose { background-color: var(--rouge); color: white; border: none; border-radius: 10px; }
        .btn-rose:hover { background-color: var(--marron); color: white; }
        .btn-outline-rose { border: 2px solid var(--rouge); color: var(--rouge); border-radius: 10px; }
        .btn-outline-rose:hover { background-color: var(--rouge); color: white; }
        .form-control, .form-select { border: 2px solid var(--rose); border-radius: 10px; }
        .form-control:focus, .form-select:focus {
            border-color: var(--rouge);
            box-shadow: 0 0 0 3px rgba(248, 187, 208, 0.4);
        }
        .section-title { border-left: 6px solid var(--rose); padding-left: 12px; margin-bottom: 20px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-sales px-4 py-2">
    <span class="navbar-brand">SALES</span>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuSales">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menuSales">
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}">Accueil</a>
            </li>
            {{-- Les 4 liens ci-dessous seront actives des la Phase 6 --}}
            <li class="nav-item"><a class="nav-link" href="#">Ventes</a></li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                href="{{ route('clients.index') }}">Clients</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('produits.*') ? 'active' : '' }}"
                href="{{ route('produits.index') }}">Produits</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="#">Factures</a></li>
        </ul>
        <ul class="navbar-nav ms-auto">
            @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Deconnexion</button>
                            </form>
                        </li>
                    </ul>
                </li>
            @endauth
        </ul>
    </div>
</nav>

<div class="container py-4">
    @include('partials.alerts')
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>