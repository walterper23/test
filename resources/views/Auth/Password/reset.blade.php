<!doctype html>
<!--[if lte IE 9]>     <html lang="es" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="es" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>{{ title('Restablecer contraseña') }}</title>

        <meta name="description" content="{{ config_var('Sistema.Nombre') }}">
        <meta name="author" content="Instituto Tecnológico de Chetumal 2017 - 2018">
        <meta name="robots" content="noindex, nofollow">
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
        <!-- Codebase framework -->
        {{ Html::style('css/codebase.min.css',['id'=>'css-main']) }}
        {{ Html::style('css/custom.codebase.css') }}
    
        <!-- END Stylesheets -->
    </head>
    <body>
        <!-- Page Container -->
        <div id="page-container" class="main-content-boxed">
            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                <div class="bg-gd-emerald">
                    <div class="hero-static content content-full bg-white invisible greca" data-toggle="appear">
                        
                        <div class="row text-center px-5">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="h2 text-primary"><strong>{!! str_replace('\n', '<br>', config_var('Institucion.Nombre')) !!}</strong></h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                {{ Html::image(config_var('Institucion.Banner.Login'),'',['width'=>'300']) }}
                            </div>
                        </div>

                        <!-- Header -->
                        <div class="py-30 px-5 text-center">
                            <h1 class="h2 font-w700 mt-30 mb-10">
                                {{ config_var('Sistema.Nombre') }}<br>
                                {{ Html::image('img/favicon/logo.png','',['width'=>'60']) }}
                            </h1>
                            <h2 class="h4 font-w400 text-muted mb-0">Iniciar sesi&oacute;n</h2>
                        </div>
                        <!-- END Header -->

                        <!-- Reminder Form -->
                        <div class="row justify-content-center px-5">
                            <div class="col-sm-8 col-md-6 col-xl-4">
                                {{ Form::open(['url'=>'/password/reset','method'=>'POST','class'=>'js-validation-reminder']) }}
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating open">
                                                {{ Form::text('username',old('username'),['id'=>'username','class'=>'form-control text-center','autofocus']) }}
                                                {{ Form::label('username','Usuario') }}
                                            </div>
                                            @if ($errors->has('username'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('username') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating open">
                                                {{ Form::password('password',['id'=>'password','class'=>'form-control text-center']) }}
                                                {{ Form::label('password','Contrase&ntilde;a') }}
                                            </div>
                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating open">
                                                {{ Form::password('password_confirmation',['id'=>'password_confirmation','class'=>'form-control text-center']) }}
                                                {{ Form::label('password_confirmation','Confirmar Contrase&ntilde;a') }}
                                            </div>
                                            @if($errors->has('password_confirmation'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-hero btn-noborder btn-rounded btn-alt-primary">
                                            <i class="fa fa-asterisk mr-10"></i> Restablecer contrase&ntilde;a
                                        </button>
                                        <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="{{ route('login') }}">
                                            <i class="si si-login text-muted mr-10"></i> Iniciar sesi&oacute;n
                                        </a>
                                    </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                        <!-- END Reminder Form -->
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

        <!-- Page JS Plugins -->
        {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}

        <!-- Page JS Code -->
        {{ Html::script('js/pages/op_auth_reset.js') }}
    </body>
</html>