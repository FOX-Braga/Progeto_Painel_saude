<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar Paciente - Curumin RES</title>
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
            max-width: 800px;
        }

        .form-group-row {
            display: flex;
            gap: 20px;
            margin-bottom: 24px;
        }

        .form-group {
            flex: 1;
            margin-bottom: 24px;
        }

        .form-group.row-item {
            margin-bottom: 0;
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

        .section-title {
            font-size: 1.1rem;
            color: var(--primary-color);
            border-bottom: 2px solid rgba(47, 133, 90, 0.1);
            padding-bottom: 8px;
            margin-top: 10px;
            margin-bottom: 20px;
            font-weight: 700;
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
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="fa-solid fa-leaf"></i></div>
            Curumin RES
        </div>
        <ul class="nav-links">
            <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link"><i class="fa-solid fa-house"></i> Geral</a></li>
            <li class="nav-item"><a href="{{ route('communities.index') }}" class="nav-link"><i class="fa-solid fa-users"></i> Comunidades</a></li>
            <li class="nav-item"><a href="{{ route('children.index') }}" class="nav-link active"><i class="fa-solid fa-notes-medical"></i> Prontuários (Lista)</a></li>
            <li class="nav-item"><a href="{{ route('profile') }}" class="nav-link"><i class="fa-solid fa-user-doctor"></i> Meu Perfil</a></li>
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
            <div class="header-title">Cadastrar Novo Paciente (Criança/Jovem)</div>
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

                <form action="{{ route('children.store') }}" method="POST">
                    @csrf

                    <div class="section-title"><i class="fa-solid fa-id-card"></i> Identificação do Paciente</div>

                    <div class="form-group">
                        <label>Nome Completo da Criança</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name') }}" required
                            placeholder="Nome">
                    </div>

                    <div class="form-group-row">
                        <div class="form-group row-item">
                            <label>Data de Nascimento</label>
                            <input type="date" name="birth_date" class="form-control"
                                value="{{ old('birth_date') }}" required>
                        </div>
                        <div class="form-group row-item">
                            <label>Gênero</label>
                            <select name="gender" class="form-control">
                                <option value="">Não informado</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group-row">
                        <div class="form-group row-item">
                            <label>Comunidade (Aldeia)</label>
                            <select name="community_id" class="form-control" required>
                                <option value="" disabled selected>-- Selecione --</option>
                                @foreach($communities as $community)
                                    <option value="{{ $community->id }}">{{ $community->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group row-item">
                            <label>Etnia / Raça</label>
                            <input type="text" name="ethnicity" class="form-control"
                                value="{{ old('ethnicity') }}"
                                placeholder="Ex: Guajajara">
                        </div>
                    </div>

                    <div class="section-title" style="margin-top: 30px;"><i class="fa-solid fa-address-book"></i> Dados Complementares</div>

                    <div class="form-group-row">
                        <div class="form-group row-item">
                            <label>CNS / CPF</label>
                            <input type="text" name="cns" class="form-control"
                                value="{{ old('cns') }}">
                        </div>
                        <div class="form-group row-item">
                            <label>Nome do Responsável</label>
                            <input type="text" name="guardian_name" class="form-control"
                                value="{{ old('guardian_name') }}">
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group row-item">
                            <label>Contato (Opcional)</label>
                            <input type="text" name="contact" class="form-control"
                                value="{{ old('contact') }}">
                        </div>
                        <div class="form-group row-item">
                            <label>Endereço na Aldeia / Localização</label>
                            <input type="text" name="address" class="form-control"
                                value="{{ old('address') }}">
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px;">
                        <a href="{{ route('children.index') }}" class="btn-cancel">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-folder-plus" style="margin-right: 8px;"></i> Cadastrar e Abrir Prontuário
                        </button>
                    </div>
                </form>
            </div>

        </section>

        <footer
            style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 0.9rem; border-top: 1px solid rgba(0,0,0,0.05); margin-top: auto;">
            &copy; {{ date('Y') }} Curumin RES - Saúde Indígena. Todos os direitos reservados.
        </footer>
    </main>
</body>

</html>
