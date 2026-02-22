<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nova Consulta - Curumin RES</title>
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍃</text></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .form-container {
            background: var(--card-bg);
            border-radius: var(--border-radius-lg);
            padding: 30px 40px;
            box-shadow: var(--shadow-sm);
            max-width: 900px;
        }

        .form-group-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 24px;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
            margin-bottom: 16px;
        }

        .form-group.full-width {
            flex: 100%;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
            background: #F7FAFC;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(47, 133, 90, 0.1);
        }

        /* Checkbox styling */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            padding: 8px 0;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }

        .checkbox-group label {
            margin: 0;
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
        }

        .section-title {
            font-size: 1.2rem;
            color: var(--primary-color);
            border-bottom: 2px solid rgba(47, 133, 90, 0.1);
            padding-bottom: 12px;
            margin-top: 30px;
            margin-bottom: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(47, 133, 90, 0.03);
            padding: 16px;
            border-radius: 8px;
        }

        .subsection-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin: 20px 0 16px;
        }

        .btn-cancel {
            background: white;
            color: var(--text-main);
            border: 1px solid #E2E8F0;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-cancel:hover {
            background: #F7FAFC;
        }

        /* Menu de abas simplificado para o mega-formulário */
        .tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 30px;
            background: #F7FAFC;
            padding: 8px;
            border-radius: 12px;
        }

        .tab-btn {
            padding: 10px 16px;
            background: transparent;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            transition: var(--transition);
            flex: 1;
            text-align: center;
            white-space: nowrap;
        }

        .tab-btn.active {
            background: white;
            color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    @include('components.sidebar')

    <main class="main-wrapper">
        <header class="top-header">
            <div class="header-title">Adicionar Evolução / Consulta</div>
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
            <div class="form-container animate-fade">
                @if($errors->any())
                    <div
                        style="background: rgba(229, 62, 62, 0.1); color: var(--accent-color); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('medical_records.store') }}" method="POST">
                    @csrf

                    <!-- 1. Identificação Inicial & Vínculo da Criança -->
                    <div class="section-title" style="margin-top: 5px;"><i class="fa-solid fa-id-card"></i> 1.
                        Identificação do Paciente</div>

                    @if(isset($child))
                        <!-- Vínculo com criança existente -->
                        <div
                            style="background: rgba(47, 133, 90, 0.05); border: 1px solid rgba(47, 133, 90, 0.2); border-radius: 8px; padding: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 16px;">
                            <div
                                style="width: 50px; height: 50px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--primary-color); box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                @if($child->gender == 'Feminino')
                                    <i class="fa-solid fa-child-dress"></i>
                                @else
                                    <i class="fa-solid fa-child"></i>
                                @endif
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.2rem; color: var(--text-main);">{{ $child->name }}</h3>
                                <div style="font-size: 0.9rem; color: var(--text-muted); margin-top: 4px;">
                                    <i class="fa-solid fa-location-dot"></i> {{ $child->community->name }} |
                                    <i class="fa-solid fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($child->birth_date)->age }} anos
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="child_id" value="{{ $child->id }}">
                    @else
                        <!-- Criação de nova criança junto com o prontuário -->
                        <div
                            style="background: rgba(42, 67, 101, 0.05); border: 1px solid rgba(42, 67, 101, 0.2); border-radius: 8px; padding: 20px; margin-bottom: 24px;">
                            <p style="margin-top: 0; color: #2A4365; font-weight: 600; margin-bottom: 16px;"><i
                                    class="fa-solid fa-info-circle"></i> Preencha os dados abaixo para cadastrar a criança e
                                seu primeiro prontuário.</p>

                            <div class="form-group-row">
                                <div class="form-group">
                                    <label>Nome Completo da Criança</label>
                                    <input type="text" name="child_name" class="form-control" placeholder="Nome do paciente"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label>Comunidade (Aldeia)</label>
                                    <select name="community_id" class="form-control" required>
                                        <option value="" disabled selected>-- Selecione --</option>
                                        @foreach($communities as $community)
                                            <option value="{{ $community->id }}">{{ $community->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group-row">
                                <div class="form-group">
                                    <label>Data de Nascimento</label>
                                    <input type="date" name="birth_date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Gênero</label>
                                    <select name="gender" class="form-control">
                                        <option value="">Não informado</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Feminino">Feminino</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Etnia / Raça</label>
                                    <input type="text" name="ethnicity" class="form-control" placeholder="Ex: Guajajara">
                                </div>
                            </div>

                            <div class="form-group-row">
                                <div class="form-group">
                                    <label>CNS / CPF</label>
                                    <input type="text" name="cns" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Nome do Responsável</label>
                                    <input type="text" name="guardian_name" class="form-control">
                                </div>
                            </div>

                            <div class="form-group-row">
                                <div class="form-group">
                                    <label>Contato (Opcional)</label>
                                    <input type="text" name="contact" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Endereço / Referência na Aldeia</label>
                                    <input type="text" name="address" class="form-control">
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="section-title" style="margin-top: 30px;"><i class="fa-solid fa-stethoscope"></i> 2.
                        Detalhes da Evolução Médica</div>
                    <div class="form-group-row">
                        <div class="form-group">
                            <label>Data da Consulta / Atendimento</label>
                            <input type="date" name="record_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Motivo da Consulta</label>
                            <input type="text" name="common[history][visit_reason]" class="form-control"
                                placeholder="Ex: Consulta de Rotina, Febre, etc" required>
                        </div>
                    </div>

                    <p style="text-align: center; color: var(--text-muted); margin: 30px 0 15px;">Os dados clínicos
                        abaixo estão separados em categorias (Tabs). Preencha apenas o necessário para esta visita
                        médica.</p>

                    <!-- Navegação de Tabs para o Formulário Gigante -->
                    <div class="tabs">
                        <button type="button" class="tab-btn active" data-tab="tab-comum"><i
                                class="fa-solid fa-stethoscope"></i> Métricas Comuns</button>
                        <button type="button" class="tab-btn" data-tab="tab-0a7"><i class="fa-solid fa-baby"></i> 0 a 7
                            Anos</button>
                        <button type="button" class="tab-btn" data-tab="tab-7a13"><i class="fa-solid fa-child"></i> 7 a
                            13 Anos</button>
                        <button type="button" class="tab-btn" data-tab="tab-14a18"><i class="fa-solid fa-person"></i> 14
                            a 18 Anos</button>
                        <button type="button" class="tab-btn" data-tab="tab-indigena"><i class="fa-solid fa-leaf"></i>
                            Específicos Indígenas</button>
                    </div>

                    <!-- TAB: MÉTRICAS COMUNS -->
                    <div id="tab-comum" class="tab-content active">
                        <div class="section-title"><i class="fa-solid fa-heart-pulse"></i> Sinais Vitais</div>
                        <div class="form-group-row">
                            <div class="form-group"><label>Pressão Arterial (PA)</label><input type="text"
                                    name="common[vitals][blood_pressure]" class="form-control" placeholder="Ex: 120/80">
                            </div>
                            <div class="form-group"><label>Frequência Cardíaca (FC)</label><input type="text"
                                    name="common[vitals][heart_rate]" class="form-control" placeholder="bpm"></div>
                            <div class="form-group"><label>Frequência Respiratória (FR)</label><input type="text"
                                    name="common[vitals][resp_rate]" class="form-control" placeholder="irpm"></div>
                            <div class="form-group"><label>Temperatura (°C)</label><input type="text"
                                    name="common[vitals][temperature]" class="form-control" placeholder="Ex: 36.5">
                            </div>
                            <div class="form-group"><label>Saturação Oxigênio (SpO₂)</label><input type="text"
                                    name="common[vitals][spo2]" class="form-control" placeholder="%"></div>
                            <div class="form-group"><label>Glicemia Capilar</label><input type="text"
                                    name="common[vitals][blood_sugar]" class="form-control" placeholder="mg/dL"></div>
                        </div>

                        <div class="form-group-row">
                            <div class="form-group"><label>Peso (kg)</label><input type="number" step="0.01"
                                    name="common[vitals][weight]" id="weight_input" class="form-control"
                                    placeholder="Ex: 45.5"></div>
                            <div class="form-group"><label>Altura (cm)</label><input type="number"
                                    name="common[vitals][height]" id="height_input" class="form-control"
                                    placeholder="Ex: 155"></div>
                            <div class="form-group"><label>IMC Calculado</label><input type="text"
                                    name="common[vitals][imc]" id="imc_input" class="form-control" readonly
                                    style="background: rgba(0,0,0,0.02); color: var(--text-main); font-weight: 500;">
                            </div>
                        </div>

                        <div class="section-title"><i class="fa-solid fa-file-waveform"></i> Histórico Clínico</div>
                        <div class="form-group-row">
                            <div class="form-group full-width"><label>Queixa Principal</label><textarea
                                    name="common[history][main_complaint]" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="form-group full-width"><label>História da Doença Atual</label><textarea
                                    name="common[history][current_disease_history]" class="form-control"
                                    rows="2"></textarea></div>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group"><label>Doenças Pré-existentes</label><input type="text"
                                    name="common[history][pre_existing]" class="form-control"></div>
                            <div class="form-group"><label>Alergias</label><input type="text"
                                    name="common[history][allergies]" class="form-control"></div>
                            <div class="form-group"><label>Uso de Medicamentos Contínuos</label><input type="text"
                                    name="common[history][continuous_meds]" class="form-control"></div>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group"><label>Histórico Cirúrgico</label><input type="text"
                                    name="common[history][surgeries]" class="form-control"></div>
                            <div class="form-group"><label>Histórico Familiar</label><input type="text"
                                    name="common[history][family_history]" class="form-control"></div>
                            <div class="form-group"><label>Internações Anteriores</label><input type="text"
                                    name="common[history][previous_admissions]" class="form-control"></div>
                        </div>

                        <div class="section-title"><i class="fa-solid fa-notes-medical"></i> Conduta Médica e Avaliação
                        </div>
                        <div class="form-group-row">
                            <div class="form-group full-width"><label>Avaliação Física (Geral, Cardiovascular,
                                    Respiratória, Abdominal, Neurológica, Dermatológica)</label><textarea
                                    name="common[evaluation][physical]" class="form-control" rows="3"></textarea></div>
                            <div class="form-group"><label>Diagnóstico (CID-10)</label><input type="text"
                                    name="common[medical_action][diagnosis]" class="form-control"></div>
                            <div class="form-group"><label>Prescrição Médica</label><textarea
                                    name="common[medical_action][prescription]" class="form-control"
                                    rows="2"></textarea></div>
                            <div class="form-group"><label>Encaminhamentos / Solicitação de Exames</label><textarea
                                    name="common[medical_action][referrals]" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: PRIMEIRA INFÂNCIA 0 A 7 -->
                    <div id="tab-0a7" class="tab-content">
                        <div class="section-title"><i class="fa-solid fa-ranking-star"></i> Crescimento, Nutrição e
                            Vacinação</div>
                        <div class="form-group-row">
                            <div class="form-group"><label>Perímetro Cefálico (0-2 anos)</label><input type="text"
                                    name="age_0_7[head_circumference]" class="form-control" placeholder="cm"></div>
                            <div class="form-group"><label>Curva de Crescimento (OMS)</label><input type="text"
                                    name="age_0_7[growth_curve]" class="form-control" placeholder="Percentil"></div>
                            <div class="form-group"><label>Índice de Desnutrição / Sobrepeso</label><input type="text"
                                    name="age_0_7[malnutrition_index]" class="form-control"></div>
                        </div>

                        <div class="subsection-title">Nutrição e Alimentação</div>
                        <div class="checkbox-group"><input type="hidden" name="age_0_7[exclusive_breastfeeding]"
                                value="0"><input type="checkbox" id="eb1" name="age_0_7[exclusive_breastfeeding]"
                                value="1"> <label for="eb1">Aleitamento Materno Exclusivo (0-6 meses)?</label></div>
                        <div class="checkbox-group"><input type="hidden" name="age_0_7[food_introduction]"
                                value="0"><input type="checkbox" id="fi1" name="age_0_7[food_introduction]" value="1">
                            <label for="fi1">Introdução Alimentar Adequada?</label>
                        </div>

                        <div class="form-group-row" style="margin-top: 15px;">
                            <div class="form-group"><label>Dosagem de Hemoglobina (Anemia)</label><input type="text"
                                    name="age_0_7[hemoglobin]" class="form-control" placeholder="g/dL"></div>
                            <div class="form-group"><label>Deficiência Vitamina A</label>
                                <select name="age_0_7[vit_a_deficiency]" class="form-control">
                                    <option value="">Não avaliado</option>
                                    <option value="yes">Sim</option>
                                    <option value="no">Não</option>
                                </select>
                            </div>
                        </div>

                        <div class="subsection-title">Cobertura Vacinal (Básica)</div>
                        <div class="form-group-row">
                            <div class="form-group">
                                <label>Vacinas Recebidas</label>
                                <div class="checkbox-group"><input type="checkbox" name="age_0_7[vaccines][bcg]"
                                        value="1" id="v_bcg"> <label for="v_bcg">BCG</label></div>
                                <div class="checkbox-group"><input type="checkbox"
                                        name="age_0_7[vaccines][pentavalente]" value="1" id="v_penta"> <label
                                        for="v_penta">Pentavalente</label></div>
                                <div class="checkbox-group"><input type="checkbox"
                                        name="age_0_7[vaccines][poliomielite]" value="1" id="v_polio"> <label
                                        for="v_polio">Poliomielite</label></div>
                                <div class="checkbox-group"><input type="checkbox"
                                        name="age_0_7[vaccines][triplice_viral]" value="1" id="v_tripli"> <label
                                        for="v_tripli">Tríplice Viral</label></div>
                                <div class="checkbox-group"><input type="checkbox" name="age_0_7[vaccines][rotavirus]"
                                        value="1" id="v_rota"> <label for="v_rota">Rotavírus</label></div>
                                <div class="checkbox-group"><input type="checkbox" name="age_0_7[vaccines][influenza]"
                                        value="1" id="v_flu"> <label for="v_flu">Influenza</label></div>
                            </div>
                            <div class="form-group">
                                <label>Taxa de Vacinação / Pendências</label>
                                <textarea name="age_0_7[vaccination_notes]" class="form-control" rows="4"></textarea>
                            </div>
                        </div>

                        <div class="section-title"><i class="fa-solid fa-brain"></i> Desenvolvimento Cognitivo, Motor e
                            Bucal</div>
                        <div class="form-group-row">
                            <div class="form-group"><label>Marcos do Desenvolvimento (Sentar/Andar)</label><input
                                    type="text" name="age_0_7[development][milestones]" class="form-control"></div>
                            <div class="form-group"><label>Linguagem e Fala</label><input type="text"
                                    name="age_0_7[development][language]" class="form-control"></div>
                            <div class="form-group"><label>Coordenação Fina e Grossa</label><input type="text"
                                    name="age_0_7[development][coordination]" class="form-control"></div>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group"><label>Frequência em Creche/Escola</label>
                                <select name="age_0_7[development][school]" class="form-control">
                                    <option value="">Não informado</option>
                                    <option value="yes">Sim</option>
                                    <option value="no">Não</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Saúde Bucal (Cáries / Higiene)</label><input type="text"
                                    name="age_0_7[dental_health]" class="form-control"></div>
                            <div class="form-group"><label>Indicadores de Alfabetização</label><input type="text"
                                    name="age_0_7[literacy]" class="form-control"></div>
                        </div>

                        <div class="subsection-title" style="color: var(--accent-color);">Indicadores de Risco</div>
                        <div class="form-group-row">
                            <div class="checkbox-group"><input type="hidden" name="age_0_7[risk][low_birth_weight]"
                                    value="0"><input type="checkbox" id="risk1" name="age_0_7[risk][low_birth_weight]"
                                    value="1"> <label for="risk1">Baixo Peso ao Nascer?</label></div>
                            <div class="checkbox-group"><input type="hidden" name="age_0_7[risk][prematurity]"
                                    value="0"><input type="checkbox" id="risk2" name="age_0_7[risk][prematurity]"
                                    value="1"> <label for="risk2">Prematuridade?</label></div>
                        </div>
                        <div class="form-group full-width"><label>Internações recenes (Diarreia, Infecção
                                Respiratória)</label><textarea name="age_0_7[risk][recent_infections]"
                                class="form-control" rows="2"></textarea></div>
                    </div>

                    <!-- TAB: INFÂNCIA 7 A 13 -->
                    <div id="tab-7a13" class="tab-content">
                        <div class="section-title"><i class="fa-solid fa-school"></i> Educação, Nutrição e Saúde Mental
                        </div>

                        <div class="subsection-title">Educação e Desenvolvimento</div>
                        <div class="form-group-row">
                            <div class="form-group"><label>Frequência Escolar e Abandono</label><input type="text"
                                    name="age_7_13[education][attendance]" class="form-control"></div>
                            <div class="form-group"><label>Defasagem Idade-Série</label><input type="text"
                                    name="age_7_13[education][lag]" class="form-control"></div>
                            <div class="form-group"><label>Desempenho (Leitura/Matemática)</label><input type="text"
                                    name="age_7_13[education][performance]" class="form-control"></div>
                        </div>

                        <div class="subsection-title">Segurança Alimentar</div>
                        <div class="form-group-row">
                            <div class="form-group"><label>Segurança Alimentar Familiar</label>
                                <select name="age_7_13[food_security]" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="Adequada">Segurança Alimentar (Adequada)</option>
                                    <option value="Insegurança Leve">Insegurança Leve</option>
                                    <option value="Insegurança Moderada">Insegurança Moderada</option>
                                    <option value="Insegurança Grave">Insegurança Grave</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Condições de Anemia</label><input type="text"
                                    name="age_7_13[anemia_status]" class="form-control"></div>
                        </div>

                        <div class="subsection-title">Saúde Mental e Social</div>
                        <div class="form-group-row">
                            <div class="form-group"><label>Avaliação Comportamental</label><input type="text"
                                    name="age_7_13[mental_health][behavior]" class="form-control"></div>
                            <div class="form-group"><label>Casos de Bullying relatados?</label><input type="text"
                                    name="age_7_13[mental_health][bullying]" class="form-control"></div>
                            <div class="form-group"><label>Sintomas de Ansiedade/Depressão</label><input type="text"
                                    name="age_7_13[mental_health][anxiety]" class="form-control"></div>
                            <div class="form-group full-width"><label>Participação em atividades culturais
                                    comunitárias</label><input type="text" name="age_7_13[social_activities]"
                                    class="form-control"></div>
                        </div>
                    </div>

                    <!-- TAB: JUVENTUDE 14 A 18 -->
                    <div id="tab-14a18" class="tab-content">
                        <div class="section-title"><i class="fa-solid fa-venus-mars"></i> Saúde Reprodutiva e Mental
                            (Juventude)</div>

                        <div class="subsection-title">Educação Sexual e Prevenção</div>
                        <div class="form-group-row">
                            <div class="form-group"><label>Vacinação HPV (Doses Tomadas)</label><input type="text"
                                    name="age_14_18[sexual_health][hpv_doses]" class="form-control"></div>
                            <div class="form-group"><label>Uso de Anticoncepcional / Preservativo</label><input
                                    type="text" name="age_14_18[sexual_health][contraception]" class="form-control">
                            </div>
                            <div class="form-group"><label>Testagem / Histórico de ISTs</label><input type="text"
                                    name="age_14_18[sexual_health][std_testing]" class="form-control"></div>
                        </div>

                        <div class="subsection-title">Ginecologia / Planejamento Familiar</div>
                        <div class="form-group-row">
                            <div class="form-group"><label>Data da Última Menstruação (DUM)</label><input type="date"
                                    name="age_14_18[gynecology][dum]" class="form-control"></div>
                            <div class="form-group"><label>Gravidez na Adolescência (Histórico/Atual)</label>
                                <select name="age_14_18[gynecology][pregnancy_history]" class="form-control">
                                    <option value="">Não/NA</option>
                                    <option value="current">Gestação Atual</option>
                                    <option value="past">Histórico Passado</option>
                                </select>
                            </div>
                            <div class="form-group full-width"><label>Atendimento Ginecológico / Urológico / Orientação
                                    Sexual</label><textarea name="age_14_18[gynecology][appointments]"
                                    class="form-control" rows="2"></textarea></div>
                        </div>

                        <div class="subsection-title">Saúde Mental (Juventude)</div>
                        <div class="form-group-row">
                            <div class="form-group"><label>Uso de Álcool ou Drogas?</label><input type="text"
                                    name="age_14_18[mental_health][substances]" class="form-control"></div>
                            <div class="form-group"><label>Tentativas de Autoagressão</label><input type="text"
                                    name="age_14_18[mental_health][self_harm]" class="form-control"></div>
                            <div class="form-group"><label>Acompanhamento Psicológico Escalas/Testes</label><input
                                    type="text" name="age_14_18[mental_health][psychology]" class="form-control"></div>
                        </div>
                    </div>

                    <!-- TAB: INDICADORES ESPECÍFICOS INDÍGENAS -->
                    <div id="tab-indigena" class="tab-content">
                        <div class="section-title"><i class="fa-solid fa-leaf"></i> Indicadores do Território e Saúde
                            Indígena</div>

                        <div class="form-group-row">
                            <div class="form-group"><label>Acesso contínuo à água potável?</label>
                                <select name="indigenous_metrics[water_access]" class="form-control">
                                    <option value="sim">Sim, satisfatório</option>
                                    <option value="parcial">Parcial / Irregular</option>
                                    <option value="nao">Não, fonte insegura</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Acesso a saneamento básico?</label>
                                <select name="indigenous_metrics[sanitation]" class="form-control">
                                    <option value="sim">Sim</option>
                                    <option value="nao">Não</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Atendimento/Acompanhamento por equipe DSEI?</label>
                                <select name="indigenous_metrics[dsei]" class="form-control">
                                    <option value="sim">Sim, regular</option>
                                    <option value="esporadico">Esporádico</option>
                                    <option value="nenhum">Nenhum</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="form-group full-width"><label>Registro de Doenças Infecciosas Prevalentes na
                                    Região (ex: Malária)</label><textarea name="indigenous_metrics[endemic_diseases]"
                                    class="form-control" rows="2"></textarea></div>
                            <div class="form-group full-width"><label>Alimentação Tradicional e Remédios da Floresta /
                                    Práticas de Cuidado Tradicional</label><textarea
                                    name="indigenous_metrics[traditional_care]" class="form-control"
                                    rows="2"></textarea></div>
                        </div>
                    </div>


                    <div
                        style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 40px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.05);">
                        @if(isset($child))
                            <a href="{{ route('children.show', $child->id) }}" class="btn-cancel">Cancelar</a>
                        @else
                            <a href="{{ route('medical_records.index') }}" class="btn-cancel">Cancelar</a>
                        @endif
                        <button type="submit" class="btn btn-primary" style="font-size: 1.1rem; padding: 12px 24px;">
                            <i class="fa-solid fa-notes-medical" style="margin-right: 8px;"></i> Salvar Evolução Médica
                        </button>
                    </div>
                </form>
            </div>

        </section>

        <footer
            style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 0.9rem; margin-top: auto;">
            &copy; {{ date('Y') }} Curumin RES - Saúde Indígena. Todos os direitos reservados.
        </footer>
    </main>

    <script>
        // Logica para TABS - Megamenu formulário
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                // remove active class
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                // add current
                this.classList.add('active');
                const target = this.getAttribute('data-tab');
                document.getElementById(target).classList.add('active');
            });
        });

        // Lógica de cálculo automático do IMC
        const weightInput = document.getElementById('weight_input');
        const heightInput = document.getElementById('height_input');
        const imcInput = document.getElementById('imc_input');

        function calculateIMC() {
            if (!weightInput || !heightInput || !imcInput) return;

            const weight = parseFloat(weightInput.value);
            const height = parseFloat(heightInput.value);

            if (!isNaN(weight) && !isNaN(height) && height > 0) {
                // height is in cm, convert to meters
                const heightMeters = height / 100;
                const imc = weight / (heightMeters * heightMeters);
                imcInput.value = imc.toFixed(2);
            } else {
                imcInput.value = '';
            }
        }

        if (weightInput && heightInput) {
            weightInput.addEventListener('input', calculateIMC);
            heightInput.addEventListener('input', calculateIMC);
        }
    </script>
</body>

</html>