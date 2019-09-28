<!doctype html>
<!--[if lte IE 9]>     <html lang="es" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="es" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>@yield('title')</title>

        <meta name="description" content="{{ config_var('Sistema.Nombre') }}">
        <meta name="author" content="Instituto Tecnológico de Chetumal 2017 - 2018">
        <meta name="robots" content="noindex, nofollow">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Open Graph Meta -->
        <!-- Open Graph Meta -->
        <meta property="og:site_name" content="{{ config_var('Sistema.Nombre') }}">
        <meta property="og:description" content="Instituto Tecnológico de Chetumal 2017 - 2018">
        <meta property="og:type" content="webapp">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{ asset('img/favicon/favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/favicon/favicon-192x192.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon-180x180.png') }}">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Page JS Plugins CSS -->
        {{ Html::style('js/plugins/sweetalert2/sweetalert2.min.css') }}
        
        @stack('css-style')

        <!-- Codebase framework -->
        {{ Html::style('css/codebase.min.css',['id'=>'css-main']) }}
        {{ Html::style('css/custom.codebase.css') }}

        <!-- END Stylesheets -->
        @stack('css-custom')

    </head>
    <body>
        <!-- Page Container -->
        <div id="page-container" class="sidebar-inverse side-scroll page-header-fixed page-header-inverse font-size-sm">
            <!-- Sidebar -->
            <nav id="sidebar">
                <!-- Sidebar Scroll Container -->
                <div id="sidebar-scroll">
                    <!-- Sidebar Content -->
                    <div class="sidebar-content">
                        <!-- Side Header -->
                        <div class="content-header content-header-fullrow bg-black-op-10">
                            <div class="content-header-section text-center align-parent">
                                <!-- Close Sidebar, Visible only on mobile screens -->
                                <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                                <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                                    <i class="fa fa-times text-danger"></i>
                                </button>
                                <!-- END Close Sidebar -->

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
                        </div>
                        <!-- END Side Header -->

                        <!-- Side Main Navigation -->
                        <div class="content-side content-side-full">
                            <!--
                            Mobile navigation, desktop navigation can be found in #page-header

                            If you would like to use the same navigation in both mobiles and desktops, you can use exactly the same markup inside sidebar and header navigation ul lists
                            -->
                            <ul class="nav-main">
                                @include('app.themes.theme2.menu')
                            </ul>
                        </div>
                        <!-- END Side Main Navigation -->
                    </div>
                    <!-- Sidebar Content -->
                </div>
                <!-- END Sidebar Scroll Container -->
            </nav>
            <!-- END Sidebar -->

            <!-- Header -->
            <header id="page-header">
                <!-- Header Content -->
                @include('app.themes.theme2.header')
                <!-- END Header Content -->

                <!-- Header Loader -->
                <!-- Please check out the Activity page under Elements category to see examples of showing/hiding it -->
                <div id="page-header-loader" class="overlay-header bg-primary">
                    <div class="content-header content-header-fullrow text-center">
                        <div class="content-header-item">
                            <i class="fa fa-sun-o fa-spin text-white"></i>
                        </div>
                    </div>
                </div>
                <!-- END Header Loader -->
            </header>
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">

                <!-- Page Content -->
                
                <!-- User Info -->
                @yield('userInfo')
                <!-- END User Info -->
                
                <!-- Main Content -->
                <div class="content p-10">
                    @yield('content')
                </div>
                <!-- END Main Content -->

                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            <footer id="page-footer" class="opacity-0" style="opacity: 1;">
                <div class="content p-10 font-size-xs clearfix">
                    <div class="float-left">
                        <span class="font-w600 text-primary">{{ config_var('Sistema.Siglas') . ' ' . config_var('Sistema.Version') }}</span> &copy; <span class="js-year-copy">2017</span>
                    </div>
                    <div class="float-right">
                        Powered by <span class="font-w600 text-danger">ITCh</span>
                    </div>
                </div>
            </footer>
            <!-- END Footer -->

        </div>
        <!-- END Page Container -->

        <!-- Codebase Core JS -->
        {{ Html::script('js/core/jquery.min.js') }}
        {{ Html::script('js/core/bootstrap.bundle.min.js') }}
        {{ Html::script('js/core/jquery.slimscroll.min.js') }}
        {{ Html::script('js/core/jquery-scrollLock.min.js') }}
        {{ Html::script('js/core/jquery.appear.min.js') }}
        {{ Html::script('js/core/js.cookie.min.js') }}
        {{ Html::script('js/plugins/vue/vue.min.js') }}
        {{ Html::script('js/plugins/vue/axios.min.js') }}
        {{ Html::script('js/codebase.js') }}
        {{ Html::script('js/app.js') }}

        {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
        {{-- Html::script('js/app-notifications.js') --}}
        {{ Html::script('js/app-alert.js') }}

        @stack('js-script')

        @stack('js-custom')
    </body>
</html>