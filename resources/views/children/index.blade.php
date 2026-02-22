<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crianças por Comunidade - Curumin RES</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍃</text></svg>">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .page-header {
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 1.6rem;
            color: var(--text-main);
        }

        .page-header p {
            color: var(--text-muted);
            margin-top: 4px;
        }

        table {
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }

        th {
            padding: 12px 16px;
            color: var(--text-muted);
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
        }

        td {
            padding: 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            vertical-align: middle;
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-adequado { background: rgba(72,187,120,0.2); color: var(--primary-color); }
        .status-atencao { background: rgba(214,158,46,0.2); color: var(--secondary-color); }
        .status-risco { background: rgba(229,62,62,0.2); color: var(--accent-color); }

        .community-section {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .community-title {
            display: flex;
            align-items: center;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 16px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 12px;
        }

        .community-title i {
            margin-right: 12px;
            color: var(--secondary-color);
        }

        .empty-state {
            padding: 24px;
            text-align: center;
            color: var(--text-muted);
            background: #F7FAFC;
            border-radius: 8px;
            font-style: italic;
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="fa-solid fa-leaf"></i></div>
            Curumin RES
        </div>
        <ul class="nav-links">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fa-solid fa-house"></i> Visão Geral
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('communities.index') }}" class="nav-link">
                    <i class="fa-solid fa-users"></i> Comunidades
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('children.index') }}" class="nav-link active">
                    <i class="fa-solid fa-child"></i> Crianças
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('medical_records.index') }}" class="nav-link">
                    <i class="fa-solid fa-notes-medical"></i> Prontuários
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile') }}" class="nav-link">
                    <i class="fa-solid fa-user-doctor"></i> Meu Perfil
                </a>
            </li>
            <li class="nav-item" style="margin-top: auto;">
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-link"
                        style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left; color: var(--accent-color);">
                        <i class="fa-solid fa-right-from-bracket"></i> Sair do Sistema
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <main class="main-wrapper">
        <header class="top-header">
            <div class="header-title">Crianças por Comunidade</div>
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
            <div class="page-header animate-fade">
                <h1>Listagem de Crianças</h1>
                <p>Veja as crianças cadastradas agrupadas por sua comunidade de origem.</p>
            </div>

            @foreach($communities as $community)
                <div class="community-section animate-fade">
                    <div class="community-title">
                        <i class="fa-solid fa-location-dot"></i>
                        Aldeia {{ $community->name }}
                        <span style="margin-left: auto; font-size: 0.9rem; color: var(--text-muted); font-weight: 400;">
                            {{ $community->children->count() }} crianças
                        </span>
                    </div>

                    @if($community->children->isEmpty())
                        <div class="empty-state">
                            Nenhuma criança registrada nesta aldeia ainda.
                        </div>
                    @else
                        <table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Data de Nasc.</th>
                                    <th>Gênero</th>
                                    <th>Última Visita</th>
                                    <th>Status Nutricional</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($community->children as $child)
                                    <tr>
                                        <td style="font-weight: 500;">{{ $child->name }}</td>
                                        <td style="color: var(--text-muted);">{{ \Carbon\Carbon::parse($child->birth_date)->format('d/m/Y') }}</td>
                                        <td style="color: var(--text-muted);">{{ $child->gender }}</td>
                                        <td style="color: var(--text-muted);">{{ $child->last_visit_date ? \Carbon\Carbon::parse($child->last_visit_date)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            @php
                                                $statusClass = 'status-adequado';
                                                if(strtolower($child->nutritional_status) == 'atenção') $statusClass = 'status-atencao';
                                                if(strtolower($child->nutritional_status) == 'risco') $statusClass = 'status-risco';
                                                if(!$child->nutritional_status) $statusClass = '';
                                            @endphp
                                            @if($child->nutritional_status)
                                                <span class="badge-status {{ $statusClass }}">{{ $child->nutritional_status }}</span>
                                            @else
                                                <span style="color: var(--text-muted);">N/A</span>
                                            @endif
                                        </td>
                                        <td><a href="#" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">Ver Ficha</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endforeach

        </section>

        <footer style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 0.9rem; border-top: 1px solid rgba(0,0,0,0.05); margin-top: auto;">
            &copy; {{ date('Y') }} Curumin RES - Saúde Indígena. Todos os direitos reservados.
        </footer>
    </main>
</body>

</html>
