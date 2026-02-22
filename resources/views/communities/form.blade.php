<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($community) ? 'Editar' : 'Nova' }} Comunidade - Curumin RES</title>
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
    @include('components.sidebar')

    <main class="main-wrapper">
        <header class="top-header">
            <div class="header-title">{{ isset($community) ? 'Editar Comunidade' : 'Cadastrar Comunidade' }}</div>
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

                <form
                    action="{{ isset($community) ? route('communities.update', $community->id) : route('communities.store') }}"
                    method="POST">
                    @csrf
                    @if(isset($community))
                        @method('PUT')
                    @endif

                    <div class="section-title"><i class="fa-solid fa-circle-info"></i> Informações Gerais da Aldeia
                    </div>

                    <div class="form-group">
                        <label>Nome da Comunidade</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $community->name ?? '') }}" required
                            placeholder="Ex: Aldeia Yawalapiti">
                    </div>

                    <div class="form-group" style="margin-bottom: 24px;">
                        <label>Endereço / Referência de Localização</label>
                        <input type="text" name="address" class="form-control"
                            value="{{ old('address', $community->address ?? '') }}"
                            placeholder="Ex: Terra Indígena do Xingu, Polo Base Polo Leonardo Villas-Bôas">
                        <small style="color: var(--text-muted); margin-top: 6px; display: block;"><i
                                class="fa-solid fa-magic"></i> Se preencher o endereço, a Latitude e Longitude serão
                            calculadas automaticamente!</small>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group row-item">
                            <label>Latitude (Opcional)</label>
                            <input type="number" step="any" name="latitude" class="form-control"
                                value="{{ old('latitude', $community->latitude ?? '') }}"
                                placeholder="Ex: -12.1500 (Auto)">
                        </div>
                        <div class="form-group row-item">
                            <label>Longitude (Opcional)</label>
                            <input type="number" step="any" name="longitude" class="form-control"
                                value="{{ old('longitude', $community->longitude ?? '') }}"
                                placeholder="Ex: -53.4000 (Auto)">
                        </div>
                    </div>

                    <div class="section-title" style="margin-top: 30px;"><i class="fa-solid fa-children"></i> Métricas
                        Demográficas Infantis</div>

                    <div class="form-group-row">
                        <div class="form-group row-item">
                            <label>Crianças (1 a 5 anos)</label>
                            <input type="number" name="population_1_to_5" class="form-control"
                                value="{{ old('population_1_to_5', $community->population_1_to_5 ?? 0) }}" required
                                min="0">
                        </div>
                        <div class="form-group row-item">
                            <label>Crianças (5 a 10 anos)</label>
                            <input type="number" name="population_5_to_10" class="form-control"
                                value="{{ old('population_5_to_10', $community->population_5_to_10 ?? 0) }}" required
                                min="0">
                        </div>
                        <div class="form-group row-item">
                            <label>Adolescentes (10 a 18 anos)</label>
                            <input type="number" name="population_10_to_18" class="form-control"
                                value="{{ old('population_10_to_18', $community->population_10_to_18 ?? 0) }}" required
                                min="0">
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px;">
                        <a href="{{ route('communities.index') }}" class="btn-cancel">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save" style="margin-right: 8px;"></i> Salvar Dados
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
