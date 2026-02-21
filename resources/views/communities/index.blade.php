<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comunidades - Curumin CRM</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍃</text></svg>">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

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

        .map-container {
            height: 400px;
            width: 100%;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 32px;
            z-index: 1;
            /* Para não sobrepor menus */
        }

        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .summary-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: var(--border-radius-md);
            border: 1px solid rgba(47, 133, 90, 0.1);
            text-align: center;
        }

        .summary-card .label {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .summary-card .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
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

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            background: #EDF2F7;
            color: #4A5568;
            margin-right: 4px;
        }

        .badge-green {
            background: rgba(72, 187, 120, 0.15);
            color: var(--primary-color);
        }

        .badge-yellow {
            background: rgba(214, 158, 46, 0.15);
            color: var(--secondary-color);
        }

        .badge-red {
            background: rgba(229, 62, 62, 0.15);
            color: var(--accent-color);
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="fa-solid fa-leaf"></i></div>
            Curumin CRM
        </div>
        <ul class="nav-links">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fa-solid fa-house"></i> Visão Geral
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('communities.index') }}" class="nav-link active">
                    <i class="fa-solid fa-users"></i> Comunidades
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
            <div class="header-title">Gestão de Comunidades</div>
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
                <div class="animate-fade" style="background: rgba(72, 187, 120, 0.1); color: var(--primary-color); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="page-header animate-fade" style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <h1>Painel Territorial e Demográfico</h1>
                    <p>Acompanhe a distribuição populacional infantil pelas regiões catalogadas.</p>
                </div>
                <div style="display: flex; gap: 16px;">
                    <form action="{{ route('communities.index') }}" method="GET" style="display: flex; align-items: center; background: white; border-radius: 8px; border: 1px solid #E2E8F0; overflow: hidden; max-width: 300px;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar aldeia, endereço..." style="border: none; padding: 10px 16px; outline: none; flex: 1; min-width: 200px;">
                        <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 10px 16px; cursor: pointer;">
                            <i class="fa-solid fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('communities.index') }}" style="color: var(--text-muted); padding: 10px; text-decoration: none;"><i class="fa-solid fa-times"></i></a>
                        @endif
                    </form>

                    <a href="{{ route('communities.create') }}" class="btn btn-primary" style="text-decoration: none; display: flex; align-items: center;">
                        <i class="fa-solid fa-plus"></i> <span style="margin-left:8px;">Nova Comunidade</span>
                    </a>
                </div>
            </div>

            <div class="stats-summary animate-fade" style="animation-delay: 0.1s;">
                <div class="summary-card">
                    <div class="label">Total de Comunidades</div>
                    <div class="value">{{ $communities->count() }}</div>
                </div>
                <div class="summary-card" style="border-color: rgba(72,187,120,0.3);">
                    <div class="label">Crianças (1 a 5 anos)</div>
                    <div class="value" style="color: #48BB78;">{{ $total1a5 }}</div>
                </div>
                <div class="summary-card" style="border-color: rgba(214,158,46,0.3);">
                    <div class="label">Crianças (5 a 10 anos)</div>
                    <div class="value" style="color: #D69E2E;">{{ $total5a10 }}</div>
                </div>
                <div class="summary-card" style="border-color: rgba(66,153,225,0.3);">
                    <div class="label">Adolescentes (10 a 18 anos)</div>
                    <div class="value" style="color: #4299E1;">{{ $total10a18 }}</div>
                </div>
                <div class="summary-card" style="background: var(--primary-color);">
                    <div class="label" style="color: rgba(255,255,255,0.8);">Total Geral de Jovens</div>
                    <div class="value" style="color: white;">{{ $totalHabitantes }}</div>
                </div>
            </div>

            <!-- Mapa -->
            <div id="map" class="map-container animate-fade" style="animation-delay: 0.2s;"></div>

            <!-- Tabela -->
            <div class="glass-panel animate-fade" style="animation-delay: 0.3s;">
                <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-list"
                        style="margin-right: 8px; color: var(--primary-color);"></i> Listagem Discriminada</h3>

                <table>
                    <thead>
                        <tr>
                            <th>Nome da Comunidade</th>
                            <th>População 1-5 anos</th>
                            <th>População 5-10 anos</th>
                            <th>População 10-18 anos</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($communities as $community)
                            <tr>
                                <td style="font-weight: 600;">
                                <div style="display: flex; align-items: center; margin-bottom: 4px;">
                                    <i class="fa-solid fa-location-dot" style="color: var(--accent-color); margin-right: 8px;"></i> {{ $community->name }}
                                </div>
                                @if($community->address)
                                    <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: normal; margin-left: 20px;">
                                        <i class="fa-regular fa-map" style="margin-right: 4px;"></i> {{ $community->address }}
                                    </div>
                                @endif
                            </td>
                            <td><span class="badge badge-green">{{ $community->population_1_to_5 }}</span></td>
                                <td><span class="badge badge-yellow">{{ $community->population_5_to_10 }}</span></td>
                                <td><span class="badge"
                                        style="background: rgba(66,153,225,0.15); color: #4299e1;">{{ $community->population_10_to_18 }}</span>
                                </td>
                                <td style="display: flex; gap: 8px;">
                                <button class="btn btn-primary" style="padding: 6px 12px; font-size: 0.85rem;" onclick="focusMap({{ $community->latitude }}, {{ $community->longitude }})">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </button>
                                <a href="{{ route('communities.edit', $community->id) }}" class="btn" style="padding: 6px 12px; font-size: 0.85rem; background: #EDF2F7; color: var(--text-main); text-decoration: none;">
                                    <i class="fa-solid fa-pen"></i> Editar
                                </a>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </section>
    </main>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Inicializa o Mapa centrado no Parque Indígena do Xingu (estimativa)
        var map = L.map('map').setView([-12.2, -53.3], 9);

        // Adiciona a camada do mapa (OpenStreetMap base)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap Curumin CRM'
        }).addTo(map);

        // Ícone customizado
        var villageIcon = L.divIcon({
            html: '<div style="background: var(--primary-color); color: white; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 8px rgba(0,0,0,0.3); border: 2px solid white;"><i class="fa-solid fa-house" style="transform: rotate(45deg); font-size: 12px;"></i></div>',
            className: '',
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -30]
        });

        // Passando dados estruturados do PHP (Laravel) para o Javascript
        var communities = @json($communities);

        // Adicionando os marcadores na tela
        communities.forEach(function (community) {
            var marker = L.marker([community.latitude, community.longitude], { icon: villageIcon }).addTo(map);

            var totalCount = community.population_1_to_5 + community.population_5_to_10 + community.population_10_to_18;

            marker.bindPopup(`
                <div style="text-align: center; min-width: 150px;">
                    <h4 style="margin: 0 0 4px; color: var(--text-main); font-family: Inter, sans-serif;">${community.name}</h4>
                    ${community.address ? `<p style="margin: 0 0 8px; font-size: 12px; color: var(--text-muted);">${community.address}</p>` : ''}
                    <p style="margin: 0; font-family: Inter, sans-serif; color: var(--text-muted); font-size: 14px;">Total de Jovens: <strong>${totalCount}</strong></p>
                </div>
            `);
        });

        // Função rápida para focar o mapa quando clica na tabela
        function focusMap(lat, lng) {
            map.setView([lat, lng], 13, {
                animate: true,
                duration: 1
            });
            window.scrollTo({ top: 300, behavior: 'smooth' });
        }
    </script>
</body>

</html>