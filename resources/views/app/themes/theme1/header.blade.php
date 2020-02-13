<div class="content-header">
    <!-- Left Section -->
    <div class="content-header-section">
        <button type="button" class="btn btn-circle btn-dual-primary" data-toggle="layout" data-action="sidebar_toggle">
            <i class="fa fa-navicon"></i>
        </button>
    </div>
    <!-- END Left Section -->

    <!-- Right Section -->
    <div class="content-header-section">
        <!-- User Dropdown -->
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-rounded btn-dual-primary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ user() -> getDescripcion() }}<i class="fa fa-angle-down ml-5"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right min-width-150" aria-labelledby="page-header-user-dropdown">
                <a class="dropdown-item" href="{{ url('usuario/perfil') }}">
                    <i class="si si-user mr-5"></i> Perfil
                </a>
                <div class="dropdown-divider"></div>

                <!-- Toggle Side Overlay -->
                <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                <a class="dropdown-item" href="{{ url('usuario/preferencias') }}" data-toggle="layout" data-action="side_overlay_toggle">
                    <i class="si si-wrench mr-5"></i> Preferencias
                </a>

                <a class="dropdown-item" href="javascript:void(0);" data-toggle="layout" data-action="sidebar_mini_toggle">
                    <i class="si si-list mr-5"></i> Mini Menú
                </a>
                <!-- END Side Overlay -->

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}">
                    <i class="si si-logout mr-5 text-danger"></i> Cerrar sesi&oacute;n
                </a>
            </div>
        </div>
        <!-- END User Dropdown -->
    </div>
    <!-- END Right Section -->
</div>