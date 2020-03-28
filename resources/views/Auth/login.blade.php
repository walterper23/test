<!doctype html>
<!--[if lte IE 9]>     <html lang="es" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="es" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>{{ title('Iniciar sesión') }}</title>

        <meta name="description" content="{{ config_var('Sistema.Nombre') }}">
        <meta name="author" content="Instituto Tecnológico de Chetumal">
        <meta name="robots" content="noindex, nofollow">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{ url('img/favicon/favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ url('img/favicon/favicon-192x192.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ url('img/favicon/apple-touch-icon-180x180.png') }}">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Codebase framework all -->
        {{ Html::style('css/codebase.min.css',['id'=>'css-main']) }}
        {{ Html::style('css/custom.codebase.css') }}

        <!-- END Stylesheets -->
    </head>
    <body>
        <div id="page-container" class="main-content-boxed">
            <main id="main-container">


                <!-- jQuery Vide for video backgrounds, for more examples you can check out https://github.com/VodkaBears/Vide -->
                <div class="bg-video" data-vide-bg="/assets/media/videos/city_night" data-vide-options="posterType: jpg">
                    <div class="hero bg-black-op">
                        <div class="hero-inner">
                            <div class="content content-full text-center">

                                 <!--div class="row text-center px-5">
                                    <div class="col-md-3 col-sm-12">
                                        {{ Html::image(config_var('Institucion.Login.Logo.Izquierdo'),'',['width'=>'200']) }}
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        {{ Html::image(config_var('Institucion.Login.Logo.Derecho'),'',['width'=>'150']) }}
                                    </div>
                                </div-->
                                <h1 class="display-4 font-w700 text-white mb-10">Instituto Municipal de Atención a la Juventud</h1>
                                <h2 class="font-w400 text-white-op mb-50">...</h2>

                                <div class="row justify-content-center px-5 text-white">
                                    <div class="col-sm-8 col-md-6 col-xl-4">
                                        {{ Form::open(['method'=>'POST', 'url'=>'login', 'class'=>'js-validation-signin']) }}
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <div class="form-material floating open" >
                                                        {{ Form::text('username',old('username'),['id'=>'username','class'=>'form-control text-center text-white','autofocus']) }}
                                                        {{ Form::label('username','Usuario') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <div class="form-material floating open">
                                                        {{ Form::password('password',['id'=>'password','class'=>'form-control text-center text-white','value'=>old('password')]) }}
                                                        {{ Form::label('password','Contrase&ntilde;a') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label class="css-control css-control-sm css-control-primary css-switch">
                                                        <input type="checkbox" class="css-control-input" id="remember" name="remember" checked="checked">
                                                        <span class="css-control-indicator"></span> Mantener sesi&oacute;n
                                                    </label>
                                                </div>
                                            </div>
                                             @if($errors->has('username'))
                                                <div class="form-group row">
                                                    <div class="col-12 text-danger text-center">
                                                        {{ $errors->first('username') }}
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group row gutters-tiny">
                                                <div class="col-12 mb-10">
                                                    <button type="submit" class="btn btn-sm btn-hero btn-noborder btn-rounded btn-success">
                                                        <i class="si si-login mr-10"></i> Ingresar
                                                    </button>
                                                </div>
                                                
                                            </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>


        <!-- Codebase Core JS -->
        {{ Html::script('js/core/jquery.min.js') }}
        {{ Html::script('js/core/bootstrap.bundle.min.js') }}
        {{ Html::script('js/core/jquery.slimscroll.min.js') }}
        {{ Html::script('js/core/jquery-scrollLock.min.js') }}
        {{ Html::script('js/core/jquery.appear.min.js') }}
        {{ Html::script('js/core/js.cookie.min.js') }}
        {{ Html::script('js/codebase.js') }}

        <!-- Page JS Plugins -->
        {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}

        <!-- Page JS Code -->
        {{ Html::script('js/pages/op_auth_signin.js') }}

        {{ Html::script('js/plugins/jquery-vide/jquery.vide.min.js') }}

    </body>
</html>