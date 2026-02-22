<aside class="sidebar">
    <div class="brand">
        <div class="brand-icon"><i class="fa-solid fa-leaf"></i></div>
        Curumin RES
    </div>
    <ul class="nav-links">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> Visão Geral
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('communities.index') }}"
                class="nav-link {{ request()->routeIs('communities.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Comunidades
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('children.index') }}"
                class="nav-link {{ request()->routeIs('children.*') ? 'active' : '' }}">
                <i class="fa-solid fa-child"></i> Crianças
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('medical_records.index') }}"
                class="nav-link {{ request()->routeIs('medical_records.*') ? 'active' : '' }}">
                <i class="fa-solid fa-notes-medical"></i> Prontuários
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
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