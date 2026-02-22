<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prontuários - Curumin RES</title>
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍃</text></svg>">

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
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="fa-solid fa-leaf"></i></div>
            Curumin RES
        </div>
        <ul class="nav-links">
            <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link"><i class="fa-solid fa-house"></i>
                    Visão Geral</a></li>
            <li class="nav-item"><a href="{{ route('communities.index') }}" class="nav-link"><i
                        class="fa-solid fa-users"></i> Comunidades</a></li>
            <li class="nav-item"><a href="{{ route('children.index') }}" class="nav-link"><i
                        class="fa-solid fa-child"></i> Crianças</a></li>
            <li class="nav-item"><a href="{{ route('medical_records.index') }}" class="nav-link active"><i
                        class="fa-solid fa-notes-medical"></i> Prontuários</a></li>
            <li class="nav-item"><a href="{{ route('profile') }}" class="nav-link"><i
                        class="fa-solid fa-user-doctor"></i> Meu Perfil</a></li>
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
            <div class="header-title">Gestão de Prontuários</div>
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
            @if(session('success'))
                <div class="animate-fade"
                    style="background: rgba(72, 187, 120, 0.1); color: var(--primary-color); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="page-header animate-fade"
                style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <h1>Registro Eletrônico de Saúde</h1>
                    <p>Acompanhe todos os prontuários médicos da comunidade infantil e adolescente.</p>
                </div>
                <div>
                    <a href="{{ route('medical_records.create') }}" class="btn btn-primary"
                        style="text-decoration: none; display: flex; align-items: center;">
                        <i class="fa-solid fa-plus"></i> <span style="margin-left:8px;">Novo Prontuário</span>
                    </a>
                </div>
            </div>

            <div class="glass-panel animate-fade" style="animation-delay: 0.1s;">
                @if($records->isEmpty())
                    <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                        <i class="fa-solid fa-folder-open" style="font-size: 3rem; margin-bottom: 16px; opacity: 0.5;"></i>
                        <p>Nenhum prontuário registrado ainda.</p>
                        <a href="{{ route('medical_records.create') }}" class="btn btn-primary"
                            style="margin-top: 16px; text-decoration: none; display: inline-block;">Criar Prontuário Completo</a>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Paciente (Criança)</th>
                                <th>Comunidade</th>
                                <th>Data do Atendimento</th>
                                <th>Médico Responsável</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                                <tr>
                                    <td style="font-weight: 600; color: var(--text-main);">{{ $record->child->name }}</td>
                                    <td style="color: var(--text-muted);">{{ $record->child->community->name }}</td>
                                    <td style="color: var(--text-muted);">
                                        {{ \Carbon\Carbon::parse($record->record_date)->format('d/m/Y') }}</td>
                                    <td style="color: var(--text-muted);">{{ $record->doctor->name }}</td>
                                    <td>
                                        <a href="{{ route('children.show', $record->child->id) }}" class="btn"
                                            style="padding: 6px 12px; font-size: 0.85rem; background: #EDF2F7; color: var(--text-main); text-decoration: none;">
                                            <i class="fa-solid fa-folder-open"></i> Abrir Prontuário
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </section>

        <footer
            style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 0.9rem; border-top: 1px solid rgba(0,0,0,0.05); margin-top: auto;">
            &copy; {{ date('Y') }} Curumin RES - Saúde Indígena. Todos os direitos reservados.
        </footer>
    </main>
</body>

</html>