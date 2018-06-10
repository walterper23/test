@extends('app.layoutMaster')

@section('title', title('Perfil de usuario') )

@section('userInfo')
<div class="bg-image bg-image-bottom" style="background-image: url('/img/background/photo23@2x.jpg');" id="context-{{ $form_id }}">
    <div class="bg-primary-dark-op">
        <div class="content content-full">
            <div class="row">
                <div class="col-md-4 text-center">
                    <!-- Avatar -->
                    <div class="mb-15">
                        <a class="img-link" href="#">
                            {!! $usuario -> presenter() -> imgAvatarSmall('img-avatar img-avatar96 img-avatar-thumb') !!}
                        </a>
                    </div>
                    <!-- END Avatar -->

                    <!-- Personal -->
                    <h1 class="h3 text-white font-w700 mb-10">{{ $usuario -> getDescripcion() }}</h1>
                    <h2 class="h5 text-white-op">
                        {{ $detalle -> presenter() -> nombreCompleto() }}
                        <a class="text-primary-light" href="javascript:void(0)">#{{ $detalle -> getNoTrabajador() }}</a>
                    </h2>
                    <!-- END Personal -->

                    <!-- Actions -->
                    <button type="button" class="btn btn-rounded btn-hero btn-sm btn-alt-primary mb-5" id="btn-ok">
                        <i class="fa fa-fw fa-check mr-5"></i> Guardar cambios
                    </button>
                    <a class="btn btn-rounded btn-hero btn-sm btn-alt-secondary mb-5" href="{{ url() -> previous() }}">
                        <i class="fa fa-times mr-5 text-danger"></i> Cancelar
                    </a>
                    <!-- END Actions -->
                </div>
                <div class="col-md-8 text-white">
                    {{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
                    {{ Form::hidden('action',1) }}
					<h4 class="text-white">Cuenta de usuario</h4>
                    <div class="row">
                        <div class="col-md-7">
                            {!! Field::text('usuario',$usuario -> getAuthUsername(),['label'=>'Nombre de usuario','disabled','readonly']) !!}
        					{!! Field::text('descripcion',$usuario -> getDescripcion(),['label'=>'Nombre de perfil','required']) !!}
        				</div>
                        <div class="col-md-5">
                            {!! Field::password('password_actual','',['label'=>'Contraseña actual','required']) !!}
        					{!! Field::password('password','',['label'=>'Nueva contraseña']) !!}
        					{!! Field::password('password_confirmation','',['label'=>'Confirme contraseña']) !!}
                        </div>
                    </div>
					<h4 class="text-white mt-20">Información personal</h4>
                    <div class="row">
    					<div class="col-md-7">
                           {!! Field::text('no_trabajador',$detalle -> getNoTrabajador(),['label'=>'Nó. trabajador','required']) !!}
                           {!! Field::text('nombres',$detalle -> getNombres(),['label'=>'Nombre(s)','required']) !!}
    					   {!! Field::text('apellidos',$detalle -> getApellidos(),['label'=>'Apellido(s)','required']) !!}
                        </div>
                        <div class="col-md-5">
                           {!! Field::select('genero',$detalle -> getGenero(),['label'=>'Género','required'],['HOMBRE'=>'HOMBRE','MUJER'=>'MUJER']) !!}
                           {!! Field::text('email',$detalle -> getEmail(),['label'=>'E-mail','required']) !!}
                           {!! Field::text('telefono',$detalle -> getTelefono(),['label'=>'Teléfono']) !!}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-script')
{{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">
    'use strict';
    var formUser = new AppForm;
    $.extend(formUser, new function(){

        this.context_ = '#context-{{ $form_id }}';
        this.form_    = '#{{ $form_id }}';
        this.btnOk_   = '#btn-ok';

        this.successSubmitHandler = function( result ){

            var config = {
                type: result.type,
                message : result.message
            };

            if( result.status ){
                config.onShown = function(){
                    location.reload();
                }
            }

            AppAlert.notify(config);
        };
    
        this.rules = function(){
            return {
                password_actual : { required : true },
                password : { minlength: 6, maxlength : 20 },
                password_confirmation : {
                    minlength: 6,
                    maxlength : 20,
                    equalTo : '#password'
                },
                no_trabajador : { maxlength : 10 },
                descripcion : { required : true, minlength : 3, maxlength : 255 },
                nombres : { required : true, minlength : 1, maxlength : 255 },
                apellidos : { required : true, minlength : 1, maxlength : 255 },
                genero : { required : true },
                email : { required : true, email : true, minlength : 5, maxlength : 255 },
                telefono : { minlength : 1, maxlength : 25 }
            }
        }

        this.messages = function(){
            return {
                password_actual : {
                    required  : 'Introduzca su contraseña actual',
                },
                password : {
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                password_confirmation : {
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres',
                    equalTo   : 'Las contraseñas no coinciden',
                },
                no_trabajador : {
                    maxlength : 'Máximo {0} caracteres'
                },
                descripcion : {
                    required : 'Introduzca su nombre de perfil',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                nombres : {
                    required : 'Introduzca su nombre(s)',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                apellidos : {
                    required : 'Introduzca sus apellidos',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                genero : {
                    required : 'Seleccione un género'
                },
                email : {
                    required : 'Introduzca el correo electrónico del usuario',
                    email    : 'Introduzca un correo electrónico válido',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                telefono : {
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                }
            }
        };

        this.displayError = function( index, value ){
            AppAlert.notify({
                type    : 'warning',
                icon    : 'fa fa-fw fa-warning',
                message : value[0],
                z_index : 9999
            });
        };

    }).init();
</script>
@endpush