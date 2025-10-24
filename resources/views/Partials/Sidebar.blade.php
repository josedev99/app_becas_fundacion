<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link " href="{{ route('app.home') }}">
                <i class="bi bi-grid"></i>
                <span>Inicio</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('becas.index') }}">
                <i class="bi bi-mortarboard"></i>
                <span>Becas</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('becados.index') }}">
                <i class="bi bi-person"></i>
                <span>Becados</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('seguimiento.index') }}">
                <i class="bi bi-folder-check"></i>
                <span>Seguimiento</span>
            </a>
        </li>

        @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser("usuario_ver")))
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('user.index') }}">
                <i class="bi bi-people"></i>
                <span>Usuario</span>
            </a>
        </li>
        @endif

    </ul>
</aside><!-- End Sidebar-->