<div class="content-side content-side-full content-side-user px-10 align-parent">
    <!-- Visible only in mini mode -->
    <div class="sidebar-mini-visible-b align-v">
        {!! user() -> presenter() -> imgAvatarSmall('img-avatar img-avatar32') !!}
    </div>
    <!-- END Visible only in mini mode -->

    <!-- Visible only in normal mode -->
    <div class="sidebar-mini-hidden sidebar-mini-hidden-b text-center">
        <a href="{{ url('perfil') }}">
            {!! user() -> presenter() -> imgAvatarSmall() !!}
        </a>
        <ul class="list-inline mt-10">
            <li class="list-inline-item">
                <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                <a class="link-effect text-dual-primary-dark" data-toggle="layout" data-action="sidebar_style_inverse_toggle" href="javascript:void(0)">
                    <strong>{{ user() -> UsuarioDetalle -> presenter() -> nombreCompleto() }}</strong>
                    <p>{{ user() -> getNombre() }}</p>
                </a>
            </li>
        </ul>
    </div>
    <!-- END Visible only in normal mode -->
</div>