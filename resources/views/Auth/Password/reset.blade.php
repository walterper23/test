<!doctype html>
<!--[if lte IE 9]>     <html lang="es" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="es" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>SIGESD :: Restablecer contrase&ntilde;a</title>

        <meta name="description" content="Sistema Gestor de Documentos :: PPA">
        <meta name="author" content="Instituto Tecnológico de Chetumal">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="Sistema Gestor de Documentos">
        <meta property="og:site_name" content="SIGESD">
        <meta property="og:description" content="Sistema Gestor de Documentos creado por Instituto Tecnológico de Chetumal">
        <meta property="og:type" content="webapp">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="/assets/img/favicons/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/assets/img/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicons/apple-touch-icon-180x180.png">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Codebase framework -->
        {{ Html::style('css/codebase.min.css',['id'=>'css-main']) }}
    
        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->
    </head>
    <body>
        <!-- Page Container -->
        <div id="page-container" class="main-content-boxed">
            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                <div class="bg-gd-cherry">
                    <div class="hero-static content content-full bg-white invisible greca" data-toggle="appear">
                        
                        <div class="row text-center px-5">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="h2 text-primary"><strong>Procuraduría de<br>Protección al Ambiente</strong></h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                {{ Html::image('img/background/banner.png','',['width'=>'300','class'=>'pull-righ']) }}
                            </div>
                        </div>

                        <!-- Header -->
                        <div class="py-30 px-5 text-center">
                            <h1 class="h2 font-w700 mt-50 mb-10">Sistema Gestor de Documentos</h1>
                            <h2 class="h4 font-w400 text-muted mb-0">Reestablecer contrase&ntilde;a</h2>
                        </div>
                        <!-- END Header -->

                        <!-- Reminder Form -->
                        <div class="row justify-content-center px-5">
                            <div class="col-sm-8 col-md-6 col-xl-4">
                                <!-- jQuery Validation (.js-validation-reminder class is initialized in js/pages/op_auth_reminder.js) -->
                                <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                {{ Form::open(['url'=>'/password/reset','method'=>'POST','class'=>'js-validation-reminder']) }}
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                {{ Form::text('email',$email or old('email'),['id'=>'email','class'=>'form-control']) }}
                                                {{ Form::label('email','Correo electr&oacute;nico') }}
                                            </div>
                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                {{ Form::password('password',['id'=>'password','class'=>'form-control']) }}
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
                                            <div class="form-material floating">
                                                {{ Form::password('password_confirmation',['id'=>'password_confirmation','class'=>'form-control']) }}
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
        {{ Html::script('js/core/popper.min.js') }}
        {{ Html::script('js/core/bootstrap.min.js') }}
        {{ Html::script('js/core/jquery.slimscroll.min.js') }}
        {{ Html::script('js/core/jquery.scrollLock.min.js') }}
        {{ Html::script('js/core/jquery.appear.min.js') }}
        {{ Html::script('js/core/jquery.countTo.min.js') }}
        {{ Html::script('js/core/js.cookie.min.js') }}
        {{ Html::script('js/codebase.js') }}

        <!-- Page JS Plugins -->
        {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}

        <!-- Page JS Code -->
        {{ Html::script('js/pages/op_auth_reminder.js') }}
    </body>
</html>