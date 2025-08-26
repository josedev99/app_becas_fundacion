<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link " href="{{ route('app.home') }}">
                <i class="bi bi-grid"></i>
                <span>Inicio</span>
            </a>
        </li>
        @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser("cuentas_ver")))
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('cuenta.index') }}">
                <i class="bi bi-person"></i>
                <span>Cuentas</span>
            </a>
        </li>
        @endif
        @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser("empresas_ver")))
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('empresa.index') }}">
                <i class="bi bi-building-fill-check"></i>
                <span>Empresas</span>
            </a>
        </li>
        @endif
        @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasAccountModule("gestion_dte")))
            <a class="nav-link collapsed" data-bs-target="#nav-dte" data-bs-toggle="collapse" href="#">
                <i class="bi bi-pc-display-horizontal"></i><span>Gestión DTE</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="nav-dte" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser("dte_emitidos_ver")))
                <li>
                    <a href="{{ route('dtes.index') }}">
                        <i class="bi bi-circle"></i><span>Documentos</span>
                    </a>
                </li>
                @endif
                @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser("dte_emitir_nota")))
                <li>
                    <a href="{{ route('dtes.notas.credito') }}">
                        <i class="bi bi-circle"></i><span>Emitir notas crédito</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('dtes.notas.debito') }}">
                <i class="bi bi-circle"></i><span>Emitir notas débito</span>
                </a>
                </li> --}}
                @endif
            </ul>
        @endif
        @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasAccountModule('compras')))
        <a class="nav-link collapsed" data-bs-target="#nav-compra" data-bs-toggle="collapse" href="#">
            <i class="bi bi-bag-check"></i><span>Compras</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="nav-compra" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser('json_compras')))
            <li>
                <a href="{{ route('compra.index') }}">
                    <i class="bi bi-circle"></i><span>Importar JSON</span>
                </a>
            </li>
            @endif
            @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser('compras_manual')))
            <li>
                <a href="{{ route('compra.manual.index') }}">
                    <i class="bi bi-circle"></i><span>Compra manual</span>
                </a>
            </li>
            @endif
            @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser('config_email')))
            <li>
                <a href="{{ route('email.index') }}">
                    <i class="bi bi-circle"></i><span>Config. Emails</span>
                </a>
            </li>
            @endif
        </ul>
        @endif
        @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasAccountModule('contabilidad')))
        <a class="nav-link collapsed" data-bs-target="#nav-contabilidad" data-bs-toggle="collapse" href="#">
            <i class="bi bi-cash-stack"></i><span>Contabilidad</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="nav-contabilidad" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser('show_catalogo_cuentas')))
            <li>
                <a href="{{ route('catalogo.cuenta.index') }}">
                    <i class="bi bi-circle"></i><span>Catálogo de cuentas</span>
                </a>
            </li>
            @endif
            @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser('caja_chica_ver')))
            <li>
                <a href="{{ route('caja.chica.index') }}">
                    <i class="bi bi-circle"></i><span>Caja chica</span>
                </a>
            </li>
            @endif
        </ul>
        @endif
        @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser("usuario_ver")))
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('user.index') }}">
                <i class="bi bi-people"></i>
                <span>Usuario</span>
            </a>
        </li>
        @endif
        <!-- PLANILLAS -->
        @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasAccountModule('planillas')))
        <a class="nav-link collapsed" data-bs-target="#nav-planilla" data-bs-toggle="collapse" href="#">
            <i class="bi bi-clipboard2-data"></i><span>Planillas</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="nav-planilla" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser('colaboradores_ver')))
            <li>
                <a href="{{ route('planilla.colab') }}">
                    <i class="bi bi-circle"></i><span>Colaboradores</span>
                </a>
            </li>
            @endif
            @if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser('areas_&_cargos_ver')))
            <li>
                <a href="{{ route('areas.colab') }}">
                    <i class="bi bi-gear"></i><span>Areas & Cargos</span>
                </a>
            </li>
            @endif
        </ul>
        @endif
    </ul>
    </ul>



</aside><!-- End Sidebar-->