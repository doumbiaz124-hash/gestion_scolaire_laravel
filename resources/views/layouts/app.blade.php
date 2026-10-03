<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion Scolaire')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #1e2a38; }
        .sidebar a { color: #cfd8e3; }
        .sidebar a.active, .sidebar a:hover { color: #fff; background: #2c3e50; }
        .navbar-brand { font-weight: 700; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .sortable-th a { color: inherit; text-decoration: none; }
        .sortable-th a:hover { text-decoration: underline; }
        @media print {
            .sidebar, .navbar, .no-print { display: none !important; }
            main { padding: 0 !important; }
            body { background: #fff !important; }
        }
    </style>
</head>
<body>
@auth
<div class="d-flex">
    <nav class="sidebar p-3" style="width: 240px;">
        <a href="{{ route('dashboard') }}" class="navbar-brand text-white d-block mb-4 fs-5">
            <i class="bi bi-mortarboard-fill"></i> Gestion Scolaire
        </a>
        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a></li>
            @if (auth()->user()->hasModule('etudiants'))
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('etudiants.*') ? 'active' : '' }}" href="{{ route('etudiants.index') }}"><i class="bi bi-people"></i> Étudiants</a></li>
            @endif
            @if (auth()->user()->hasModule('enseignants'))
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('enseignants.*') ? 'active' : '' }}" href="{{ route('enseignants.index') }}"><i class="bi bi-person-badge"></i> Enseignants</a></li>
            @endif
            @if (auth()->user()->hasModule('classes'))
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}" href="{{ route('classes.index') }}"><i class="bi bi-building"></i> Classes</a></li>
            @endif
            @if (auth()->user()->hasModule('series'))
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('series.*') ? 'active' : '' }}" href="{{ route('series.index') }}"><i class="bi bi-diagram-3"></i> Séries</a></li>
            @endif
            @if (auth()->user()->hasModule('emploi_du_temps'))
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('emploi-du-temps.*') ? 'active' : '' }}" href="{{ route('emploi-du-temps.index') }}"><i class="bi bi-calendar3"></i> Emploi du temps</a></li>
            @endif
            @if (auth()->user()->hasModule('bulletins'))
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('matieres.*') ? 'active' : '' }}" href="{{ route('matieres.index') }}"><i class="bi bi-list-check"></i> Matières</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('notes.*') ? 'active' : '' }}" href="{{ route('notes.choisir-etudiant') }}"><i class="bi bi-pencil-square"></i> Saisie des notes</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('bulletins.*') ? 'active' : '' }}" href="{{ route('bulletins.choisir-etudiant') }}"><i class="bi bi-journal-text"></i> Bulletins</a></li>
            @endif
            @if (auth()->user()->hasModule('paiements'))
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('paiements.*') ? 'active' : '' }}" href="{{ route('paiements.index') }}"><i class="bi bi-cash-coin"></i> Paiements</a></li>
            @endif
            @if (auth()->user()->isAdmin())
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}"><i class="bi bi-shield-lock"></i> Rôles</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('utilisateurs.*') ? 'active' : '' }}" href="{{ route('utilisateurs.index') }}"><i class="bi bi-person-gear"></i> Utilisateurs</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('promotion.*') ? 'active' : '' }}" href="{{ route('promotion.index') }}"><i class="bi bi-arrow-up-circle"></i> Passage classe sup.</a></li>
            @endif
        </ul>
    </nav>
    <div class="flex-grow-1">
        <nav class="navbar navbar-light bg-white border-bottom px-4">
            <span class="navbar-text">Connecté en tant que <strong>{{ auth()->user()->email }}</strong></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>
            </form>
        </nav>
        <main class="p-4">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
@else
    @yield('content')
@endauth
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
