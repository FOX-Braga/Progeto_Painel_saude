<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perfil - Curumin CRM</title>
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍃</text></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .profile-card {
            background: var(--card-bg);
            border-radius: var(--border-radius-lg);
            padding: 40px;
            box-shadow: var(--shadow-md);
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .profile-photo-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 24px;
        }

        .profile-photo {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary-light);
            box-shadow: var(--shadow-sm);
        }

        .profile-photo-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            border: 4px solid var(--primary-light);
        }

        .upload-btn {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: var(--secondary-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid white;
            transition: var(--transition);
        }

        .upload-btn:hover {
            transform: scale(1.1);
        }

        #photo-upload-input {
            display: none;
        }

        .profile-info h2 {
            font-size: 1.8rem;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .profile-info p {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .alert-success {
            background: rgba(72, 187, 120, 0.1);
            color: var(--primary-color);
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-weight: 500;
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
                <a href="{{ route('profile') }}" class="nav-link active">
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
            <div class="header-title">Meu Perfil</div>
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
            <div class="profile-card animate-fade">
                @if(session('success'))
                    <div class="alert-success">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div
                        style="background: rgba(229, 62, 62, 0.1); color: var(--accent-color); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="profile-photo-container">
                    @if($user->profile_photo_path)
                        <img src="{{ Storage::url($user->profile_photo_path) }}" alt="Foto de Perfil" class="profile-photo">
                    @else
                        <div class="profile-photo-placeholder">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                    @endif

                    <form id="photo-form" action="{{ route('profile.updatePhoto') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <label for="photo-upload-input" class="upload-btn" title="Alterar Foto">
                            <i class="fa-solid fa-camera"></i>
                        </label>
                        <input type="file" id="photo-upload-input" name="photo"
                            accept="image/png, image/jpeg, image/gif"
                            onchange="document.getElementById('photo-form').submit();">
                    </form>
                </div>

                <div class="profile-info">
                    <h2>{{ $user->name }}</h2>
                    <p>CRM/Registro: {{ $user->email }}</p>
                </div>
            </div>
        </section>
    </main>
</body>

</html>