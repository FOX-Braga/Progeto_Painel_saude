<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Curumin CRM</title>
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍃</text></svg>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            background-image: linear-gradient(135deg, rgba(47, 133, 90, 0.05) 0%, rgba(214, 158, 46, 0.05) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.03);
            position: relative;
        }

        .login-header {
            background: var(--primary-color);
            padding: 40px 30px 30px;
            text-align: center;
            color: white;
        }

        .login-header i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.9);
        }

        .login-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
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

        .btn-full {
            width: 100%;
            padding: 14px;
            font-size: 1.05rem;
            display: flex;
            justify-content: center;
        }

        .alert-error {
            background: rgba(229, 62, 62, 0.1);
            color: var(--accent-color);
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>

<body class="animate-fade">
    <div class="login-container">
        <div class="login-header">
            <i class="fa-solid fa-staff-snake"></i>
            <h2>Curumin CRM</h2>
            <p>Acesso exclusivo para Médicos e Especialistas</p>
        </div>

        <div class="login-body">
            @if ($errors->any())
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Usuário de Acesso</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Ex: adimin" required
                        autofocus>
                </div>

                <div class="form-group" style="margin-bottom: 32px;">
                    <label for="password">Senha Protegida</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••"
                        required>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    Acessar Painel <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i>
                </button>
            </form>
        </div>
    </div>
</body>

</html>