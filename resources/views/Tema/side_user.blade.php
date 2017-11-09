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
        <a class="link-effect text-dual-primary-dark font-size-xs font-w600 text-uppercase" href="{{ url('perfil') }}">{{ $user->getNombre() }}</a>
    </div>
    <!-- END Visible only in normal mode -->
</div>