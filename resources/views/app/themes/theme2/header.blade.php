<div class="content-header p-10">
    <!-- Left Section -->
    <div class="content-header-section">
        <!-- Logo -->
        <div class="content-header-item">
            <a class="link-effect font-w700" href="/">
                <span class="font-size-xl text-dual-primary-dark">
                    {{ Html::image('img/favicon/logo.png','Logo',['class'=>'mb-5','width'=>'30']) }} {{ config_var('Sistema.Siglas') }}
                </span>
            </a>
        </div>
        <!-- END Logo -->
    </div>
    <!-- END Left Section -->

    <!-- Middle Section -->
    <div class="content-header-section d-none d-lg-block">
        <!-- Header Navigation -->
        <ul class="nav-main-header">
            @include('app.themes.theme2.menu')
        </ul>
        <!-- END Header Navigation -->
    </div>
    <!-- END Middle Section -->

    <!-- Right Section -->
    <div class="content-header-section">

        <!-- Notifications -->
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-bell"></i>
                <span id="icon-bell-notification" class="badge badge-primary badge-pill d-none">5</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right min-width-300 pr-5 pl-5" aria-labelledby="page-header-notifications">
                <h5 class="h6 text-center mb-5 pb-5 border-b text-uppercase">Notificaciones</h5>
                <ul id="list-notifications" class="list-unstyled my-5">
                    <li>
                        <a class="text-body-color-dark media mb-15" href="javascript:void(0)">
                            <div class="ml-5 mr-15">
                                <i class="fa fa-fw fa-check text-success"></i>
                            </div>
                            <div class="media-body pr-10">
                                <p class="mb-0">You’ve upgraded to a VIP account successfully!</p>
                                <div class="text-muted font-size-sm font-italic">15 min ago</div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="text-body-color-dark media mb-15" href="javascript:void(0)">
                            <div class="ml-5 mr-15">
                                <i class="fa fa-fw fa-exclamation-triangle text-warning"></i>
                            </div>
                            <div class="media-body pr-10">
                                <p class="mb-0">Please check your payment info since we can’t validate them!</p>
                                <div class="text-muted font-size-sm font-italic">50 min ago</div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="text-body-color-dark media mb-15" href="javascript:void(0)">
                            <div class="ml-5 mr-15">
                                <i class="fa fa-fw fa-times text-danger"></i>
                            </div>
                            <div class="media-body pr-10">
                                <p class="mb-0">Web server stopped responding and it was automatically restarted!</p>
                                <div class="text-muted font-size-sm font-italic">4 hours ago</div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="text-body-color-dark media mb-15" href="javascript:void(0)">
                            <div class="ml-5 mr-15">
                                <i class="fa fa-fw fa-exclamation-triangle text-warning"></i>
                            </div>
                            <div class="media-body pr-10">
                                <p class="mb-0">Please consider upgrading your plan. You are running out of space.</p>
                                <div class="text-muted font-size-sm font-italic">16 hours ago</div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="text-body-color-dark media mb-15" href="javascript:void(0)">
                            <div class="ml-5 mr-15">
                                <i class="fa fa-fw fa-plus text-primary"></i>
                            </div>
                            <div class="media-body pr-10">
                                <p class="mb-0">New purchases! +$250</p>
                                <div class="text-muted font-size-sm font-italic">1 day ago</div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END Notifications -->

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
                <a class="dropdown-item" href="{{ route('logout') }}">
                    <i class="si si-logout mr-5 text-danger"></i> Cerrar sesi&oacute;n
                </a>
            </div>
        </div>
        <!-- END User Dropdown -->

        <!-- Toggle Sidebar -->
        <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
        <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
            <i class="fa fa-navicon"></i>
        </button>
        <!-- END Toggle Sidebar -->
    </div>
    <!-- END Right Section -->
</div>