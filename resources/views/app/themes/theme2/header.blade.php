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
        <div class="btn-group" role="group" id="header-notifications">
            <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-bell"></i>
                <span id="icon-bell-notification" class="badge badge-primary badge-pill d-none">5</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right min-width-300 pr-5 pl-5" aria-labelledby="page-header-notifications">
                <h5 class="h6 text-center mb-5 pb-5 border-b text-uppercase">Notificaciones</h5>
                <ul id="list-notifications" class="list-unstyled my-5">
                    <li v-for="notificacion in notificaciones">
                        <div class="text-body-color-dark media mb-15" >
                            <div class="ml-5 mr-15" v-html="notificacion.badge">
                                @{{ notificacion.badge }}
                            </div>
                            <div class="media-body pr-10">
                                <p class="mb-0" v-html="notificacion.contenido">@{{ notificacion.contenido }}</p>
                                <div class="text-muted font-size-sm font-italic">
                                    @{{ notificacion.fecha }} <a v-if="notificacion.url != '#'" :href="notificacion.url">Ver más...</a>
                                </div>
                            </div>
                        </div>
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