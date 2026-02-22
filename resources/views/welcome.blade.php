<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Curumin RES</title>
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍃</text></svg>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar animate-fade">
        <div class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-leaf"></i>
            </div>
            Curumin RES
        </div>

        <ul class="nav-links">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link active">
                    <i class="fa-solid fa-house"></i>
                    Visão Geral
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('communities.index') }}" class="nav-link">
                    <i class="fa-solid fa-users"></i>
                    Comunidades
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('children.index') }}" class="nav-link">
                    <i class="fa-solid fa-child"></i>
                    Crianças
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('medical_records.index') }}" class="nav-link">
                    <i class="fa-solid fa-notes-medical"></i>
                    Prontuários
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile') }}" class="nav-link">
                    <i class="fa-solid fa-user-doctor"></i>
                    Meu Perfil
                </a>
            </li>
            <li class="nav-item" style="margin-top: auto;">
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-link"
                        style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left; color: var(--accent-color);">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Sair do Sistema
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-wrapper">
        <header class="top-header animate-fade">
            <div class="header-title">Olá, Profissional de Saúde 👋</div>
            <div class="user-profile">
                <a href="{{ route('profile') }}"
                    style="text-decoration: none; display: flex; align-items: center; gap: 16px;">
                    <span style="font-weight: 500; color: var(--text-muted);">{{ Auth::user()->name }}</span>
                    <div class="avatar" style="overflow: hidden;">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="Avatar"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fa-solid fa-user-doctor"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <section class="content-area">

            <div class="stats-grid animate-fade" style="animation-delay: 0.1s;">
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fa-solid fa-child"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Crianças Atendidas</h4>
                        <div class="value">142</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon yellow">
                        <i class="fa-solid fa-building-user"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Comunidades</h4>
                        <div class="value">5</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon red">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Vacinas Pendentes</h4>
                        <div class="value">28</div>
                    </div>
                </div>
            </div>

            <div class="glass-panel animate-fade" style="animation-delay: 0.2s;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h3>Atendimentos Recentes</h3>
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i> Novo Registro
                    </button>
                </div>

                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid rgba(0,0,0,0.05); color: var(--text-muted);">
                            <th style="padding: 12px 16px;">Nome da Criança</th>
                            <th style="padding: 12px 16px;">Comunidade</th>
                            <th style="padding: 12px 16px;">Última Visita</th>
                            <th style="padding: 12px 16px;">Status Nutricional</th>
                            <th style="padding: 12px 16px;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                            <td style="padding: 16px; font-weight: 500;">Tainá Yawalapiti</td>
                            <td style="padding: 16px; color: var(--text-muted);">Alto Xingu</td>
                            <td style="padding: 16px; color: var(--text-muted);">Hoje, 09:30</td>
                            <td style="padding: 16px;"><span
                                    style="background: rgba(72,187,120,0.2); color: var(--primary-color); padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Adequado</span>
                            </td>
                            <td style="padding: 16px;"><a href="#"
                                    style="color: var(--primary-color); font-weight: 600; text-decoration: none;">Ver
                                    Ficha</a></td>
                        </tr>
                        <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                            <td style="padding: 16px; font-weight: 500;">Cauã Guajajara</td>
                            <td style="padding: 16px; color: var(--text-muted);">Arariboia</td>
                            <td style="padding: 16px; color: var(--text-muted);">Ontem, 14:15</td>
                            <td style="padding: 16px;"><span
                                    style="background: rgba(214,158,46,0.2); color: var(--secondary-color); padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Atenção</span>
                            </td>
                            <td style="padding: 16px;"><a href="#"
                                    style="color: var(--primary-color); font-weight: 600; text-decoration: none;">Ver
                                    Ficha</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </section>

        <footer
            style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 0.9rem; border-top: 1px solid rgba(0,0,0,0.05); margin-top: auto;">
            &copy; {{ date('Y') }} Curumin RES - Saúde Indígena. Todos os direitos reservados.
        </footer>
    </main>
</body>

</html>