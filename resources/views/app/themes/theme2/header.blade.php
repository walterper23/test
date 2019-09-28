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
        <!--div class="btn-group" role="group" id="header-notifications">
            <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-fw fa-bell"></i><span id="icon-bell-notification" class="badge badge-primary badge-pill d-none"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-right min-width-300 pr-5 pl-5" aria-labelledby="page-header-notifications">
                <h5 class="h6 text-center mb-5 pb-5 border-b text-uppercase">Notificaciones</h5>
                <ul id="list-notifications" class="list-unstyled my-5">
                    <li v-if="!notificaciones.length">
                        <div class="text-body-color-dark media mb-0" >
                            <div class="media-body pr-10">
                                <div class="text-muted font-size-sm text-center">
                                    - No hay notificaciones -
                                </div>
                            </div>
                        </div>
                    </li>
                    <li v-else v-for="notificacion in notificaciones">
                        <div class="text-body-color-dark media mb-10" >
                            <div class="ml-5 mr-5 text-center">
                                <span v-html="notificacion.badge">@{{ notificacion.badge }}</span>
                                <br><a href="javascript:void(0)" @click.prevent="_eliminarNotificacion(notificacion)" title="Eliminar notificación"><i class="fa fa-times text-danger"></i></a>
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
        </div-->
        <!-- END Notifications -->

        <!-- User Dropdown -->
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-rounded btn-dual-primary d-none d-md-inline" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ user()->UsuarioDetalle->presenter()->getNombreCompleto() }}
                <i class="fa fa-angle-down ml-5"></i>
            </button>
            <button type="button" class="btn btn-rounded btn-dual-primary d-xs-inline d-sm-inline d-md-none" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-user"></i><i class="fa fa-angle-down ml-5"></i></button>
            <div class="dropdown-menu dropdown-menu-right min-width-150" aria-labelledby="page-header-user-dropdown">
                <div class="dropdown-item text-center">
                    {!! user()->presenter()->imgAvatarSmall() !!}<br>
                    <b>{{ user()->UsuarioDetalle->getNoTrabajador() }} :: {{ user()->UsuarioDetalle->presenter()->getNombreCompleto() }}</b><br>
                    {{ user()->getDescripcion() }}
                </div>
                <a class="dropdown-item" href="{{ url('usuario/perfil') }}">
                    <i class="si si-user mr-5"></i> Modificar perfil
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