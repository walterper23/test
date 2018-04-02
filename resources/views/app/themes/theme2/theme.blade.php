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
        @stack('css-style')

        <!-- Codebase framework -->
        {{ Html::style('css/codebase.min.css',['id'=>'css-main']) }}
        {{ Html::style('css/custom.codebase.css') }}

        <!-- END Stylesheets -->
        @stack('css-custom')

    </head>
    <body>
        <!-- Page Container -->
        <div id="page-container" class="sidebar-inverse side-scroll page-header-fixed page-header-inverse main-content-boxed">
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
                                <li>
                                    <a href="bd_dashboard.html"><i class="si si-compass"></i>Dashboard</a>
                                </li>
                                <li class="nav-main-heading">Layout</li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-puzzle"></i>Variations</a>
                                    <ul>
                                        <li>
                                            <a href="bd_variations_hero_simple_1.html">Hero Simple 1</a>
                                        </li>
                                        <li>
                                            <a href="bd_variations_hero_simple_2.html">Hero Simple 2</a>
                                        </li>
                                        <li>
                                            <a href="bd_variations_hero_simple_3.html">Hero Simple 3</a>
                                        </li>
                                        <li>
                                            <a href="bd_variations_hero_simple_4.html">Hero Simple 4</a>
                                        </li>
                                        <li>
                                            <a href="bd_variations_hero_image_1.html">Hero Image 1</a>
                                        </li>
                                        <li>
                                            <a href="bd_variations_hero_image_2.html">Hero Image 2</a>
                                        </li>
                                        <li>
                                            <a href="bd_variations_hero_image_3.html">Hero Image 3</a>
                                        </li>
                                        <li>
                                            <a href="bd_variations_hero_image_4.html">Hero Image 4</a>
                                        </li>
                                        <li>
                                            <a href="bd_variations_hero_video_1.html">Hero Video 1</a>
                                        </li>
                                        <li>
                                            <a href="bd_variations_hero_video_2.html">Hero Video 2</a>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">More Options</a>
                                            <ul>
                                                <li>
                                                    <a href="javascript:void(0)">Another Link</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)">Another Link</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)">Another Link</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-main-heading">Other Pages</li>
                                <li>
                                    <a class="active" href="bd_search.html"><i class="si si-magnifier"></i>Search</a>
                                </li>
                                <li>
                                    <a href="be_pages_dashboard.html"><i class="si si-action-undo"></i>Go Back</a>
                                </li>
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

                <!-- Breadcrumb -->
                <div class="bg-body-light border-b">
                    <div class="content py-5 text-center">
                        @yield('breadcrumb')
                    </div>
                </div>
                <!-- END Breadcrumb -->

                <!-- Page Content -->
                <div class="content">
                    @yield('content')
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
        {{ Html::script('js/app.js') }}

        @stack('js-script')

        @stack('js-custom')
    </body>
</html>