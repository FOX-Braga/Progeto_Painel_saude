<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel de Saúde: {{ $community->name }} - Curumin RES</title>
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍃</text></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2f855a 100%);
            color: white;
            padding: 30px;
            border-radius: var(--border-radius-lg);
            margin-bottom: 30px;
            box-shadow: var(--shadow-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-header h1 {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .dashboard-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: var(--border-radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .chart-card h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-card h3 i {
            color: var(--primary-color);
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .alert-banner {
            background: rgba(229, 62, 62, 0.1);
            color: var(--accent-color);
            padding: 16px 20px;
            border-radius: var(--border-radius-md);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            border-left: 4px solid var(--accent-color);
        }
    </style>
</head>

<body>
    @include('components.sidebar')

    <main class="main-wrapper">
        <header class="top-header">
            <div class="header-title">Inteligência Coletiva: {{ $community->name }}</div>
            <div class="user-profile">
                <a href="{{ route('profile') }}"
                    style="text-decoration: none; display: flex; align-items: center; gap: 16px;">
                    <span style="font-weight: 500; color: var(--text-muted);">{{ Auth::user()->name }}</span>
                    <div class="avatar" style="overflow: hidden;">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                </a>
            </div>
        </header>

        <section class="content-area">

            <!-- Botão Voltar -->
            <div style="margin-bottom: 20px;">
                <a href="{{ route('communities.index') }}" class="btn"
                    style="background: white; color: var(--text-main); text-decoration: none; border: 1px solid #E2E8F0;">
                    <i class="fa-solid fa-arrow-left"></i> Voltar para Comunidades
                </a>
            </div>

            <div class="dashboard-header animate-fade">
                <div>
                    <h1><i class="fa-solid fa-chart-line"></i> Painel Epidemiológico - Aldeia {{ $community->name }}
                    </h1>
                    <p>Mapeamento de Saúde Pública e Vigilância Territorial baseada em Prontuários.</p>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 2.5rem; font-weight: 700;">{{ $community->children->count() }}</div>
                    <div style="font-size: 0.9rem; opacity: 0.9;">Crianças Monitoradas</div>
                </div>
            </div>

            @php
                $totalAnemia = array_sum($stats['anemia']);
                $percAnemia = $totalAnemia > 0 ? round(($stats['anemia']['Baixa (Anemia)'] / $totalAnemia) * 100) : 0;
            @endphp
            @if($percAnemia >= 20)
                <div class="alert-banner animate-fade" style="animation-delay: 0.1s;">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.5rem;"></i>
                    <div>
                        <strong>ALERTA DE SEGURANÇA ALIMENTAR:</strong> {{ $percAnemia }}% das crianças avaliadas na aldeia
                        estão com índices de anemia abaixo do ideal.
                    </div>
                </div>
            @endif

            <div class="charts-grid animate-fade" style="animation-delay: 0.2s;">
                <!-- 1. Mapa Nutricional -->
                <div class="chart-card">
                    <h3><i class="fa-solid fa-apple-whole"></i> Mapa Nutricional</h3>
                    <div class="chart-wrapper">
                        <canvas id="nutritionChart"></canvas>
                    </div>
                    <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; margin-top: 10px;">
                        Distribuição por Status Nutricional Cadastrado</p>
                </div>

                <!-- 2. Índice de Anemia -->
                <div class="chart-card">
                    <h3><i class="fa-solid fa-droplet" style="color: #E53E3E;"></i> Índice de Anemia Secundária</h3>
                    <div class="chart-wrapper">
                        <canvas id="anemiaChart"></canvas>
                    </div>
                    <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; margin-top: 10px;">Taxa
                        de Hemoglobina Abaixo de 11 g/dL</p>
                </div>

                <!-- 3. Cobertura Vacinal -->
                <div class="chart-card">
                    <h3><i class="fa-solid fa-syringe" style="color: #3182CE;"></i> Cobertura Vacinal (PNI)</h3>
                    <div class="chart-wrapper">
                        <canvas id="vaccineChart"></canvas>
                    </div>
                    <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; margin-top: 10px;">
                        Monitoramento de Atualização Caderneta</p>
                </div>

                <!-- 4. Casos Respiratórios por Período -->
                <div class="chart-card">
                    <h3><i class="fa-solid fa-lungs-virus" style="color: #805AD5;"></i> Incidência Respiratória Temporal
                    </h3>
                    <div class="chart-wrapper">
                        <canvas id="respiratoryChart"></canvas>
                    </div>
                    <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; margin-top: 10px;">Série
                        histórica de casos (Pneumonia, Asma, Gripes)</p>
                </div>

                <!-- 5. Distribuição Etária -->
                <div class="chart-card" style="grid-column: 1 / -1;">
                    <h3><i class="fa-solid fa-cake-candles" style="color: #DD6B20;"></i> Pirâmide Etária (Idades)</h3>
                    <div class="chart-wrapper">
                        <canvas id="ageChart"></canvas>
                    </div>
                    <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; margin-top: 10px;">
                        Quantidade de crianças agrupadas por idade em anos</p>
                </div>
            </div>

        </section>

        <footer
            style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 0.9rem; border-top: 1px solid rgba(0,0,0,0.05); margin-top: auto;">
            &copy; {{ date('Y') }} Curumin RES - Inteligência Epidemiológica Local.
        </footer>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stats = @json($stats);

            Chart.defaults.font.family = "'Inter', sans-serif";
            const commonPieOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            };

            // 1. Nutrição (Doughnut)
            const ctxNutri = document.getElementById('nutritionChart').getContext('2d');
            new Chart(ctxNutri, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(stats.nutrition),
                    datasets: [{
                        data: Object.values(stats.nutrition),
                        backgroundColor: ['#48BB78', '#D69E2E', '#E53E3E'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    ...commonPieOptions,
                    cutout: '60%'
                }
            });

            // 2. Anemia (Pie)
            const ctxAnemia = document.getElementById('anemiaChart').getContext('2d');
            new Chart(ctxAnemia, {
                type: 'pie',
                data: {
                    labels: Object.keys(stats.anemia),
                    datasets: [{
                        data: Object.values(stats.anemia),
                        backgroundColor: ['#3182CE', '#E53E3E'],
                        borderWidth: 0,
                    }]
                },
                options: commonPieOptions
            });

            // 3. Vacinas (Polar ou Doughnut)
            const ctxVax = document.getElementById('vaccineChart').getContext('2d');
            new Chart(ctxVax, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(stats.vaccines),
                    datasets: [{
                        data: Object.values(stats.vaccines),
                        backgroundColor: ['#48BB78', '#ED8936'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    ...commonPieOptions,
                    cutout: '50%'
                }
            });

            // 4. Respiratório (Line ou Bar)
            const ctxResp = document.getElementById('respiratoryChart').getContext('2d');
            new Chart(ctxResp, {
                type: 'bar',
                data: {
                    labels: Object.keys(stats.respiratory),
                    datasets: [{
                        label: 'Número de Notificações / Consultas',
                        data: Object.values(stats.respiratory),
                        backgroundColor: 'rgba(128, 90, 213, 0.7)',
                        borderColor: '#805AD5',
                        borderWidth: 2,
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // 5. Distribuição Etária (Bar)
            const ctxAge = document.getElementById('ageChart').getContext('2d');
            new Chart(ctxAge, {
                type: 'bar',
                data: {
                    labels: Object.keys(stats.ages).map(age => age + ' anos'),
                    datasets: [{
                        label: 'Número de Crianças',
                        data: Object.values(stats.ages),
                        backgroundColor: 'rgba(221, 107, 32, 0.7)',
                        borderColor: '#DD6B20',
                        borderWidth: 2,
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

        });
    </script>
</body>

</html>