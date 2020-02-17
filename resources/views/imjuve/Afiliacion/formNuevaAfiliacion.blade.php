@extends('vendor.modal.template',['headerColor'=>'bg-default'])

@section('title')<i class="fa fa-fw fa-user-plus"></i> {!! $title !!}@endsection

@section('content')
{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id,'files'=>true]) }}
    {{ Form::hidden('action',$action) }}
    <div class="row">
        <div class="block col-lg-12">
            <div class="block-header block-header-default">
                <h3 class="block-title">Datos personales</h3>
            </div>
            <div class="block-content row">
                <div class="col-md-8">
                    {!! Field::text('nombres','',['label'=>'Nombre(s)','required','maxlength'=>255]) !!}
                    {!! Field::text('paterno','',['label'=>'A. Paterno','required','maxlength'=>255]) !!}
                    {!! Field::text('materno','',['label'=>'A. Materno','required','maxlength'=>255]) !!}
                    {!! Field::select('genero','',['label'=>'Género','required'],$generos) !!}
                    {!! Field::datepicker('nacimiento',date('Y-m-d'),['label'=>'F. Nacimiento','required','placeholder'=>date('Y-m-d'),'popover'=>['F. Nacimiento','Introduzca la fecha de nacimiento del afiliado']]) !!}
                </div>
                <div class="col-md-4">
                </div>
                <div class="col-md-12">
                    {!! Field::select('escolaridad','',['label'=>'Escolaridad'],$escolaridades) !!}
                    {!! Field::select('ecivil','',['label'=>'Estado Civil'],$estados_civiles) !!}
                    {!! Field::select('ocupacion','',['label'=>'Ocupación'],$ocupaciones) !!}
                    {!! Field::select('nacionalidad','',['label'=>'Nacionalidad'],$nacionalidades) !!}
                </div>
            </div>
        </div>
        <div class="block col-lg-12">
            <div class="block-header block-header-default">
                <h3 class="block-title">Dirección y contacto</h3>
            </div>
            <div class="block-content row">
            
            </div>
        </div>
    </div>
{{ Form::close() }}
@endsection
@push('js-script')
    {{ Html::script('js/helpers/recepcion.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush
@push('js-custom')
<script type="text/javascript">
	'use strict';
    var formAfiliacion = new AppForm;
	$.extend(formAfiliacion, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';

        this.start = function(){

            var self = this;
            Codebase.helpers(['datepicker','select2']);

            self.form.on('keyup keypress', function (e) {
                var code = e.keyCode || e.which;

                if (code === 13) {
                    e.preventDefault();
                    return false;
                }
            });

        };
	
		this.rules = function(){
			return {
                usuario : { required : true, email : true, maxlength : 255 },
				password : { required : true, minlength: 6, maxlength : 20 },
                password_confirmation : { required : true, minlength: 6, maxlength : 20, equalTo : '#password' },
                notrabajador : { maxlength : 10 },
                descripcion : { required : true, minlength : 3, maxlength : 255 },
                nombres : { required : true, minlength : 1, maxlength : 255 },
                apellidos : { required : true, minlength : 1, maxlength : 255 },
                genero : { required : true },
                email : { required : true, email : true, minlength : 5, maxlength : 255 },
                telefono : { maxlength : 25 }
			}
		}

		this.messages = function(){
			return {
				usuario : {
                    required  : 'Introduzca un nombre usuario',
                    email     : 'Introduzca un correo electrónico válido',
                    maxlength : 'Máximo {0} caracteres'
                },
                password : {
                    required  : 'Introduzca una contraseña',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                password_confirmation : {
                    required  : 'Confirme la contraseña',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres',
                    equalTo   : 'Las contraseñas no coinciden',
                },
                notrabajador : {
                    maxlength : 'Máximo {0} caracteres'
                },
                descripcion : {
                    required : 'Introduzca la descripción del usuario',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                nombres : {
                    required : 'Introduzca el nombre(s) del usuario',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                apellidos : {
                    required : 'Introduzca los apellidos del usuario',
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

	}).init().start();
</script>
@endpush