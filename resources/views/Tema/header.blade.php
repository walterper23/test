<div class="content-header">
    <!-- Left Section -->
    <div class="content-header-section">
        
    </div>
    <!-- END Left Section -->

    <!-- Right Section -->
    <div class="content-header-section">
        <!-- User Dropdown -->
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-rounded btn-dual-primary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->getNombre() }}<i class="fa fa-angle-down ml-5"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right min-width-150" aria-labelledby="page-header-user-dropdown">
                <a class="dropdown-item" href="{{ url('perfil') }}">
                    <i class="si si-user mr-5"></i> Perfil
                </a>
                <a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_inbox.html">
                    <span><i class="si si-envelope-open mr-5"></i> Inbox</span>
                    <span class="badge badge-primary">3</span>
                </a>
                <a class="dropdown-item" href="be_pages_generic_invoice.html">
                    <i class="si si-note mr-5"></i> Invoices
                </a>
                <div class="dropdown-divider"></div>

                <!-- Toggle Side Overlay -->
                <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                <a class="dropdown-item" href="{{ url('configuracion/preferencias') }}" data-toggle="layout" data-action="side_overlay_toggle">
                    <i class="si si-wrench mr-5"></i> Preferencias
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