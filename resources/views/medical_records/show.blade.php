<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evolução de {{ $medicalRecord->child->name }} - Curumin RES</title>
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍃</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .record-card {
            background: var(--card-bg);
            border-radius: var(--border-radius-lg);
            padding: 30px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
        }

        .record-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header-info h2 {
            font-size: 1.5rem;
            color: var(--text-main);
            margin-bottom: 5px;
        }

        .header-info p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .header-actions .btn {
            font-weight: 600;
        }

        .section-title {
            color: var(--primary-color);
            font-size: 1.1rem;
            font-weight: 700;
            margin-top: 25px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .data-box {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .data-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .data-value {
            font-size: 1.05rem;
            font-weight: 500;
            color: var(--text-main);
        }

        .text-box {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
            line-height: 1.6;
            color: var(--text-main);
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    @include('components.sidebar')

    <main class="main-wrapper">
        <header class="top-header">
            <div class="header-title">
                <a href="{{ route('children.show', $medicalRecord->child_id) }}"
                    style="color: var(--text-muted); text-decoration: none; margin-right: 10px;"><i
                        class="fa-solid fa-arrow-left"></i> Voltar</a>
                Detalhes da Evolução
            </div>
            <div class="user-profile">
                <span style="font-weight: 500; color: var(--text-muted);">{{ Auth::user()->name }}</span>
                <div class="avatar" style="overflow: hidden;">
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="Avatar"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="fa-solid fa-user-doctor"></i>
                    @endif
                </div>
            </div>
        </header>

        <section class="content-area">
            <div class="record-card animate-fade">
                <div class="record-header">
                    <div class="header-info">
                        <h2>Evolução Clínica e Antropométrica</h2>
                        <p><i class="fa-solid fa-user"></i> Criança: <strong>{{ $medicalRecord->child->name }}</strong>
                            ({{ $medicalRecord->child->community->name }})</p>
                        <p><i class="fa-solid fa-calendar"></i> Data:
                            {{ \Carbon\Carbon::parse($medicalRecord->record_date)->format('d/m/Y') }}</p>
                        <p><i class="fa-solid fa-user-doctor"></i> Profissional: Dr(a).
                            {{ $medicalRecord->doctor->name }}</p>
                    </div>
                    <div class="header-actions">
                        <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print"></i>
                            Imprimir</button>
                    </div>
                </div>

                @php
                    $vitals = $medicalRecord->data['common']['vitals'] ?? [];
                    $history = $medicalRecord->data['common']['history'] ?? [];
                    $action = $medicalRecord->data['common']['medical_action'] ?? [];
                    $age07 = $medicalRecord->data['age_0_7'] ?? [];
                    $age714 = $medicalRecord->data['age_7_14'] ?? [];
                @endphp

                <!-- Sinais Vitais -->
                @if(!empty(array_filter($vitals)))
                    <div class="section-title"><i class="fa-solid fa-heart-pulse"></i> Sinais Vitais e Antropometria</div>
                    <div class="data-grid">
                        @if(!empty($vitals['weight']))
                            <div class="data-box">
                                <div class="data-label">Peso</div>
                                <div class="data-value">{{ $vitals['weight'] }} kg</div>
                        </div>@endif
                        @if(!empty($vitals['height']))
                            <div class="data-box">
                                <div class="data-label">Altura</div>
                                <div class="data-value">{{ $vitals['height'] }} cm</div>
                        </div>@endif
                        @if(!empty($vitals['imc']))
                            <div class="data-box">
                                <div class="data-label">IMC</div>
                                <div class="data-value">{{ $vitals['imc'] }}</div>
                        </div>@endif
                        @if(!empty($vitals['temperature']))
                            <div class="data-box">
                                <div class="data-label">Temperatura</div>
                                <div class="data-value">{{ $vitals['temperature'] }} °C</div>
                        </div>@endif
                        @if(!empty($vitals['blood_pressure']))
                            <div class="data-box">
                                <div class="data-label">P. Arterial</div>
                                <div class="data-value">{{ $vitals['blood_pressure'] }} mmHg</div>
                        </div>@endif
                        @if(!empty($vitals['heart_rate']))
                            <div class="data-box">
                                <div class="data-label">Freq. Cardíaca</div>
                                <div class="data-value">{{ $vitals['heart_rate'] }} bpm</div>
                        </div>@endif
                        @if(!empty($vitals['respiratory_rate']))
                            <div class="data-box">
                                <div class="data-label">Freq. Respiratória</div>
                                <div class="data-value">{{ $vitals['respiratory_rate'] }} ipm</div>
                        </div>@endif
                        @if(!empty($vitals['oxygen_saturation']))
                            <div class="data-box">
                                <div class="data-label">Saturação</div>
                                <div class="data-value">{{ $vitals['oxygen_saturation'] }} %</div>
                        </div>@endif
                    </div>
                @endif

                <!-- Anamnese -->
                @if(!empty(array_filter($history)))
                    <div class="section-title"><i class="fa-regular fa-clipboard"></i> Anamnese e Histórico Clínico</div>
                    @if(!empty($history['main_complaint']))
                        <div class="data-label">Queixa Principal e HDA</div>
                        <div class="text-box">{{ $history['main_complaint'] }}</div>
                    @endif
                    @if(!empty($history['medical_history']))
                        <div class="data-label">Histórico Médico e Cirúrgico</div>
                        <div class="text-box">{{ $history['medical_history'] }}</div>
                    @endif
                    @if(!empty($history['allergies']))
                        <div class="data-label">Alergias / Restrições</div>
                        <div class="text-box" style="border-left-color: #E53E3E;">{{ $history['allergies'] }}</div>
                    @endif
                @endif

                <!-- Exames Lab Infância (0-7 ou 7-14) -->
                @if(!empty(array_filter($age07)) || !empty(array_filter($age714)))
                    <div class="section-title"><i class="fa-solid fa-microscope"></i> Exames e Rastreio</div>
                    <div class="data-grid">
                        @if(!empty($age07['hemoglobin']))
                            <div class="data-box">
                                <div class="data-label">Hemoglobina</div>
                                <div class="data-value">{{ $age07['hemoglobin'] }} g/dL</div>
                        </div>@endif
                        @if(!empty($age07['iron_supplements']))
                            <div class="data-box">
                                <div class="data-label">Supl. Ferro</div>
                                <div class="data-value">{{ $age07['iron_supplements'] == '1' ? 'Em Uso' : 'Não' }}</div>
                        </div>@endif
                        @if(!empty($age07['breastfeeding']))
                            <div class="data-box">
                                <div class="data-label">Aleitamento</div>
                                <div class="data-value">{{ ucfirst(str_replace('_', ' ', $age07['breastfeeding'])) }}</div>
                        </div>@endif
                    </div>
                @endif

                <!-- Conduta Médica -->
                @if(!empty(array_filter($action)))
                    <div class="section-title"><i class="fa-solid fa-stethoscope"></i> Diagnóstico e Conduta</div>
                    @if(!empty($action['diagnosis']))
                        <div class="data-label">Diagnóstico (Hipóteses ou CID)</div>
                        <div class="text-box" style="border-left-color: var(--secondary-color);">{{ $action['diagnosis'] }}
                        </div>
                    @endif
                    @if(!empty($action['prescription']))
                        <div class="data-label">Prescrição e Orientações</div>
                        <div class="text-box">{{ $action['prescription'] }}</div>
                    @endif
                    @if(!empty($action['referral']))
                        <div class="data-label">Encaminhamento</div>
                        <div class="text-box" style="border-left-color: var(--accent-color);">{{ $action['referral'] }}</div>
                    @endif
                    @if(!empty($action['return_date']))
                        <div class="data-label">Data de Retorno</div>
                        <div class="data-box" style="display: inline-block; margin-top: 5px;">
                            {{ \Carbon\Carbon::parse($action['return_date'])->format('d/m/Y') }}</div>
                    @endif
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