<div class="content-side content-side-full content-side-user px-10 align-parent">
    <!-- Visible only in mini mode -->
    <div class="sidebar-mini-visible-b align-v animated fadeIn">
        {{ Html::image('img/avatars/no-profile.jpg','',['class'=>'img-avatar img-avatar32']) }}
    </div>
    <!-- END Visible only in mini mode -->

    <!-- Visible only in normal mode -->
    <div class="sidebar-mini-hidden-b text-center">
        <a class="img-link" href="{{ url('perfil') }}">
            {{ Html::image('img/avatars/no-profile.jpg','',['class'=>'img-avatar']) }}
        </a>
        <ul class="list-inline mt-10">
            <li class="list-inline-item">
                <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                <a class="link-effect text-dual-primary-dark" data-toggle="layout" data-action="sidebar_style_inverse_toggle" href="javascript:void(0)">
                    {{ $user->usuarioDetalle->presenter()->nombreCompleto() }}
                    <br>
                    {{ $user->getNombre() }}
                </a>
            </li>
        </ul>
    </div>
    <!-- END Visible only in normal mode -->
</div>