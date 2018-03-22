<!doctype html>
<!--[if lte IE 9]>     <html lang="es" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="es" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>{{ title('403 Acceso no autorizado') }}</title>

        <meta name="description" content="{{ config_var('Sistema.Nombre') }}">
        <meta name="author" content="Instituto Tecnológico de Chetumal 2017 - 2018">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:site_name" content="{{ config_var('Sistema.Nombre') }}">
        <meta property="og:description" content="Instituto Tecnológico de Chetumal 2017 - 2018">
        <meta property="og:type" content="webapp">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{ asset(config_var('Sistema.Icono')) }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/favicon/favicon-192x192.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon-180x180.png') }}">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Codebase framework -->
        {{ Html::style('css/codebase.min.css',['id'=>'css-main']) }}

        <!-- END Stylesheets -->
    </head>
    <body>
        <!-- Page Container -->
        <div id="page-container" class="main-content-boxed">
            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                <div class="hero bg-white">
                    <div class="hero-inner">
                        <div class="content content-full">
                            <div class="py-30 text-center">
                                <div class="display-3 text-corporate">
                                    <i class="fa fa-ban"></i> 403
                                </div>
                                <h1 class="h2 font-w700 mt-30 mb-10">Oops.. Lo sentimos</h1>
                                <h2 class="h3 font-w400 text-muted mb-50">Pero no tienes permiso para acceder a esta página</h2>
                                <a class="btn btn-hero btn-rounded btn-alt-secondary" href="{{ url()->previous() }}">
                                    <i class="fa fa-arrow-left mr-10"></i> Regresar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->

        <!-- Codebase Core JS -->
        {{ Html::script('js/core/jquery.min.js') }}
        {{ Html::script('js/core/bootstrap.bundle.min.js') }}
        {{ Html::script('js/core/jquery.slimscroll.min.js') }}
        {{ Html::script('js/core/jquery.scrollLock.min.js') }}
        {{ Html::script('js/core/jquery.appear.min.js') }}
        {{ Html::script('js/core/jquery.countTo.min.js') }}
        {{ Html::script('js/core/js.cookie.min.js') }}
        {{ Html::script('js/codebase.js') }}
    </body>
</html>