<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prontuário - {{ $child->name }} - Curumin RES</title>
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
        .page-header {
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 1.8rem;
            color: var(--text-main);
        }

        .page-header p {
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Banner com resumo do paciente */
        .patient-banner {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: var(--border-radius-lg);
            padding: 30px;
            color: white;
            display: flex;
            align-items: center;
            gap: 24px;
            box-shadow: 0 10px 25px rgba(47, 133, 90, 0.2);
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .patient-banner::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M10,50 Q40,10 90,50 T190,50' fill='none' stroke='rgba(255,255,255,0.1)' stroke-width='2'/%3E%3C/svg%3E") repeat;
            opacity: 0.5;
            pointer-events: none;
        }

        .patient-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            flex-shrink: 0;
            border: 3px solid rgba(255, 255, 255, 0.5);
        }

        .patient-info {
            flex: 1;
        }

        .patient-info h2 {
            font-size: 1.8rem;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .patient-badges {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .p-badge {
            background: rgba(255, 255, 255, 0.15);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            backdrop-filter: blur(4px);
            font-weight: 500;
        }

        .timeline-container {
            position: relative;
            padding-left: 30px;
        }

        .timeline-container::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10px;
            bottom: 0;
            width: 2px;
            background: #E2E8F0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            background: var(--card-bg);
            border-radius: var(--border-radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-sm);
        }

        .timeline-icon {
            position: absolute;
            left: -42px;
            top: 20px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            border: 4px solid #F7FAFC;
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .t-date {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .t-doctor {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .timeline-body {
            color: var(--text-main);
            font-size: 0.95rem;
        }

        .metric-pill {
            display: inline-flex;
            flex-direction: column;
            background: #F7FAFC;
            padding: 8px 12px;
            border-radius: 8px;
            margin-right: 8px;
            margin-bottom: 8px;
            min-width: 100px;
            border: 1px solid #EDF2F7;
        }

        .m-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 600;
        }

        .m-value {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-main);
            margin-top: 2px;
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
                    Geral</a></li>
            <li class="nav-item"><a href="{{ route('communities.index') }}" class="nav-link"><i
                        class="fa-solid fa-users"></i> Comunidades</a></li>
            <li class="nav-item"><a href="{{ route('children.index') }}" class="nav-link active"><i
                        class="fa-solid fa-notes-medical"></i> Prontuários (Lista)</a></li>
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
            <div class="header-title">Prontuário Médico</div>
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

            <!-- BANNER PACIENTE -->
            <div class="patient-banner animate-fade">
                <div class="patient-avatar">
                    @if($child->gender == 'Feminino')
                        <i class="fa-solid fa-child-dress"></i>
                    @else
                        <i class="fa-solid fa-child"></i>
                    @endif
                </div>
                <div class="patient-info">
                    <h2>{{ $child->name }}</h2>
                    <div class="patient-badges">
                        <span class="p-badge"><i class="fa-solid fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($child->birth_date)->age }} anos
                            ({{ \Carbon\Carbon::parse($child->birth_date)->format('d/m/Y') }})</span>
                        <span class="p-badge"><i class="fa-solid fa-location-dot"></i>
                            {{ $child->community->name }}</span>
                        @if($child->ethnicity)
                            <span class="p-badge"><i class="fa-solid fa-id-card"></i> {{ $child->ethnicity }}</span>
                        @endif
                        @if($child->cns)
                            <span class="p-badge"><i class="fa-solid fa-hashtag"></i> CNS: {{ $child->cns }}</span>
                        @endif
                    </div>
                </div>
                <div>
                    <!-- Link para abrir uma nova consulta passando o ID do paciente na URL -->
                    <a href="{{ route('medical_records.create', ['child_id' => $child->id]) }}"
                        style="background: white; color: var(--primary-color); font-weight: 700; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: transform 0.2s;">
                        <i class="fa-solid fa-stethoscope" style="margin-right: 8px;"></i> Iniciar Consulta
                    </a>
                </div>
            </div>

            <div style="display: flex; gap: 30px;" class="animate-fade" style="animation-delay: 0.1s;">
                <!-- COLUNA ESQUERDA: LINHA DO TEMPO E GRÁFICOS -->
                <div style="flex: 2;">
                    <!-- GRÁFICOS DE SAÚDE -->
                    @if(count(array_filter($chartData['weights'])) > 0 || count(array_filter($chartData['heights'])) > 0 || count(array_filter($chartData['imcs'])) > 0 || count(array_filter($chartData['temps'])) > 0 || count(array_filter($chartData['hemoglobins'])) > 0)
                    <div style="margin-bottom: 40px;">
                        <h3 style="margin-bottom: 20px; color: var(--text-main); font-size: 1.3rem;">
                            <i class="fa-solid fa-chart-pie"></i> Painel de Saúde e Evolução
                        </h3>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <!-- Crescimento (Peso/Altura/IMC) -->
                            <div style="background: var(--card-bg); border-radius: var(--border-radius-lg); padding: 20px; box-shadow: var(--shadow-sm);">
                                <h4 style="margin-bottom: 15px; font-size: 1rem; color: var(--text-main);"><i class="fa-solid fa-weight-scale" style="color: var(--primary-color);"></i> Crescimento e IMC</h4>
                                <div style="width: 100%; height: 220px;">
                                    <canvas id="growthChart"></canvas>
                                </div>
                            </div>
                            
                            <!-- Sinais Vitais (Temp/FC) -->
                            <div style="background: var(--card-bg); border-radius: var(--border-radius-lg); padding: 20px; box-shadow: var(--shadow-sm);">
                                <h4 style="margin-bottom: 15px; font-size: 1rem; color: var(--text-main);"><i class="fa-solid fa-heart-pulse" style="color: var(--accent-color);"></i> Sinais Vitais</h4>
                                <div style="width: 100%; height: 220px;">
                                    <canvas id="vitalsChart"></canvas>
                                </div>
                            </div>
                            
                            <!-- Exames / Laboratorial (Hemoglobina) -->
                            <div style="background: var(--card-bg); border-radius: var(--border-radius-lg); padding: 20px; box-shadow: var(--shadow-sm); grid-column: span 2;">
                                <h4 style="margin-bottom: 15px; font-size: 1rem; color: var(--text-main);"><i class="fa-solid fa-vial" style="color: var(--secondary-color);"></i> Evolução Laboratorial (Hemoglobina)</h4>
                                <div style="width: 100%; height: 200px;">
                                    <canvas id="labChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <h3 style="margin-bottom: 24px; color: var(--text-main); font-size: 1.3rem;"><i
                            class="fa-solid fa-clock-rotate-left"></i> Histórico de Consultas</h3>

                    @if($child->medical_records->isEmpty())
                        <div
                            style="background: var(--card-bg); padding: 40px; text-align: center; border-radius: var(--border-radius-lg); color: var(--text-muted);">
                            <i class="fa-solid fa-folder-open"
                                style="font-size: 3rem; margin-bottom: 16px; opacity: 0.3;"></i>
                            <p>O prontuário foi aberto, mas ainda não possui nenhuma consulta ou evolução registrada.</p>
                            <p style="margin-top: 10px;">Clique em <strong>"Iniciar Consulta"</strong> para registrar o
                                primeiro atendimento.</p>
                        </div>
                    @else
                        <div class="timeline-container">
                            @foreach($child->medical_records->sortByDesc('record_date') as $record)
                                <div class="timeline-item">
                                    <div class="timeline-icon"><i class="fa-solid fa-check"></i></div>
                                    <div class="timeline-header">
                                        <div>
                                            <div class="t-date">
                                                {{ \Carbon\Carbon::parse($record->record_date)->format('d/m/Y') }}</div>
                                            <div class="t-doctor"><i class="fa-solid fa-user-doctor"></i> Dr(a).
                                                {{ $record->doctor->name }}</div>
                                        </div>
                                        <div>
                                            <a href="#" class="btn btn-primary"
                                                style="padding: 6px 12px; font-size: 0.85rem; text-decoration: none;"><i
                                                    class="fa-solid fa-file-medical"></i> Ver Completo</a>
                                        </div>
                                    </div>
                                    <div class="timeline-body">
                                        <!-- Destaques -->
                                        @if(isset($record->data['common']['vitals']))
                                            @php $vitals = $record->data['common']['vitals']; @endphp
                                            <div style="margin-bottom: 16px;">
                                                @if(!empty($vitals['blood_pressure']))
                                                    <div class="metric-pill"><span class="m-label">PA</span><span
                                                class="m-value">{{ $vitals['blood_pressure'] }}</span></div>@endif
                                                @if(!empty($vitals['weight']))
                                                    <div class="metric-pill"><span class="m-label">Peso</span><span
                                                class="m-value">{{ $vitals['weight'] }} kg</span></div>@endif
                                                @if(!empty($vitals['temperature']))
                                                    <div class="metric-pill"><span class="m-label">Temp</span><span
                                                class="m-value">{{ $vitals['temperature'] }} °C</span></div>@endif
                                            </div>
                                        @endif

                                        @if(isset($record->data['common']['history']['main_complaint']) && !empty($record->data['common']['history']['main_complaint']))
                                            <div style="margin-bottom: 12px;">
                                                <strong style="color: var(--secondary-color);">Queixa Principal:</strong>
                                                <p style="margin-top: 4px;">
                                                    {{ $record->data['common']['history']['main_complaint'] }}</p>
                                            </div>
                                        @endif

                                        @if(isset($record->data['common']['medical_action']['diagnosis']) && !empty($record->data['common']['medical_action']['diagnosis']))
                                            <div style="margin-bottom: 12px;">
                                                <strong style="color: var(--secondary-color);">Diagnóstico / CID:</strong>
                                                <p style="margin-top: 4px;">
                                                    {{ $record->data['common']['medical_action']['diagnosis'] }}</p>
                                            </div>
                                        @endif

                                        @if(isset($record->data['common']['medical_action']['prescription']) && !empty($record->data['common']['medical_action']['prescription']))
                                            <div style="margin-bottom: 12px;">
                                                <strong style="color: var(--secondary-color);">Prescrição / Conduta:</strong>
                                                <div
                                                    style="margin-top: 4px; background: #F7FAFC; padding: 10px; border-radius: 6px; border-left: 3px solid var(--primary-color);">
                                                    {{ $record->data['common']['medical_action']['prescription'] }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- COLUNA DIREITA: INFO ADICIONAL -->
                <div style="flex: 1;">
                    <div
                        style="background: var(--card-bg); border-radius: var(--border-radius-lg); padding: 24px; box-shadow: var(--shadow-sm); position: sticky; top: 20px;">
                        <h4
                            style="margin-bottom: 20px; color: var(--text-main); font-size: 1.1rem; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 10px;">
                            <i class="fa-solid fa-address-card" style="color: var(--primary-color);"></i> Acervo Pessoal
                        </h4>

                        <div style="margin-bottom: 16px;">
                            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 2px;">Data de
                                Nascimento</div>
                            <div style="font-weight: 500;">
                                {{ \Carbon\Carbon::parse($child->birth_date)->format('d/m/Y') }}</div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 2px;">Responsável
                            </div>
                            <div style="font-weight: 500;">{{ $child->guardian_name ?? 'Não informado' }}</div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 2px;">Contato</div>
                            <div style="font-weight: 500;">{{ $child->contact ?? 'Não informado' }}</div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 2px;">Gênero</div>
                            <div style="font-weight: 500;">{{ $child->gender ?? 'Não informado' }}</div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 2px;">Endereço
                            </div>
                            <div style="font-weight: 500;">{{ $child->address ?? 'Não informado' }}</div>
                        </div>
                    </div>

                    </div>
                </div>
            </div>

        </section>

        <footer
            style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 0.9rem; border-top: 1px solid rgba(0,0,0,0.05); margin-top: auto;">
            &copy; {{ date('Y') }} Curumin RES - Saúde Indígena. Todos os direitos reservados.
        </footer>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);
            
            // Common Options
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 12, family: "'Inter', sans-serif" }, usePointStyle: true } }
                },
                scales: { x: { grid: { display: false } } }
            };

            // 1. Growth Chart (Weight & IMC)
            const growthCtx = document.getElementById('growthChart');
            if (growthCtx && (chartData.weights.some(x => x !== null) || chartData.imcs.some(x => x !== null))) {
                new Chart(growthCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Peso (kg)',
                                data: chartData.weights,
                                borderColor: '#48BB78',
                                backgroundColor: 'rgba(72, 187, 120, 0.1)',
                                borderWidth: 2, tension: 0.3, fill: true, spanGaps: true,
                                yAxisID: 'y'
                            },
                            {
                                label: 'IMC',
                                data: chartData.imcs,
                                borderColor: '#3182CE',
                                backgroundColor: 'rgba(49, 130, 206, 0.1)',
                                borderWidth: 2, tension: 0.3, spanGaps: true, borderDash: [5, 5],
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            x: { grid: { display: false } },
                            y: { type: 'linear', display: true, position: 'left', title: { display: true, text: 'Peso (kg)' } },
                            y1: { type: 'linear', display: true, position: 'right', title: { display: true, text: 'IMC' }, grid: { drawOnChartArea: false } }
                        }
                    }
                });
            }

            // 2. Vitals Chart (Temp & Heart Rate)
            const vitalsCtx = document.getElementById('vitalsChart');
            if (vitalsCtx && (chartData.temps.some(x => x !== null) || chartData.fcs.some(x => x !== null))) {
                new Chart(vitalsCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Temp (°C)',
                                data: chartData.temps,
                                borderColor: '#E53E3E',
                                backgroundColor: 'rgba(229, 62, 62, 0.1)',
                                borderWidth: 2, tension: 0.3, fill: true, spanGaps: true,
                                yAxisID: 'y'
                            },
                            {
                                label: 'FC (bpm)',
                                data: chartData.fcs,
                                borderColor: '#DD6B20',
                                backgroundColor: 'transparent',
                                borderWidth: 2, tension: 0.3, spanGaps: true,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            x: { grid: { display: false } },
                            y: { type: 'linear', display: true, position: 'left', title: { display: true, text: 'Temperatura' }, min: 35, max: 41 },
                            y1: { type: 'linear', display: true, position: 'right', title: { display: true, text: 'FC' }, grid: { drawOnChartArea: false } }
                        }
                    }
                });
            }

            // 3. Lab Chart (Hemoglobin)
            const labCtx = document.getElementById('labChart');
            if (labCtx && chartData.hemoglobins.some(x => x !== null)) {
                new Chart(labCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Hemoglobina (g/dL)',
                                data: chartData.hemoglobins,
                                backgroundColor: '#805AD5',
                                borderRadius: 4,
                            },
                            // Threshold line reference (Optional visual aid)
                            {
                                label: 'Mínimo Ideal (11 g/dL)',
                                data: chartData.labels.map(() => 11),
                                type: 'line',
                                borderColor: '#E53E3E',
                                borderDash: [5, 5],
                                borderWidth: 2,
                                pointRadius: 0,
                                fill: false
                            }
                        ]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            y: { beginAtZero: true, max: 18 }
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>