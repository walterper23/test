<!doctype html>
<!--[if lte IE 9]>     <html lang="en" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>@yield('title')</title>
        
        <meta name="description" content="Sistema Gestor de Documentos :: PPA">
        <meta name="author" content="Instituto Tecnológico de Chetumal">
        <meta name="robots" content="noindex, nofollow">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="Sistema Gestor de Documentos">
        <meta property="og:site_name" content="SIGESD">
        <meta property="og:description" content="Sistema Gestor de Documentos creado por Instituto Tecnológico de Chetumal">
        <meta property="og:type" content="webapp">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{ asset('img/favicons/favicon.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/favicons/favicon-192x192.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicons/apple-touch-icon-180x180.png') }}">
        <!-- END Icons -->
        
        <!-- Stylesheets -->
        <!-- Page JS Plugins CSS -->
        @stack('css-style')

        <!-- Codebase framework -->
        {{ Html::style('css/codebase.css',['id'=>'css-main']) }}

        <!-- END Stylesheets -->
        @stack('css-custom')

    </head>
    <body>


        <div id="page-container" class="main-content-boxed">
            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                <div class="hero bg-white">
                    <div class="hero-inner">
                        <div class="content content-full">
                            <div class="py-30 text-center">
                                <div class="display-3 text-danger">
                                    <i class="fa fa-warning"></i> 404
                                </div>
                                <h1 class="h2 font-w700 mt-30 mb-10">Oops.. Ocurrió un error en la solicitud</h1>
                                <h2 class="h3 font-w400 text-muted mb-50">Lo sentimos, pero la página solicitada no fue encontrada</h2>
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
        {{ Html::script('js/core/popper.min.js') }}
        {{ Html::script('js/core/bootstrap.min.js') }}
        {{ Html::script('js/core/jquery.slimscroll.min.js') }}
        {{ Html::script('js/core/jquery.scrollLock.min.js') }}
        {{ Html::script('js/core/jquery.appear.min.js') }}
        {{ Html::script('js/core/jquery.countTo.min.js') }}
        {{ Html::script('js/core/js.cookie.min.js') }}
        {{ Html::script('js/codebase.js') }}

        @stack('js-script')

        @stack('js-custom')

    </body>
</html>