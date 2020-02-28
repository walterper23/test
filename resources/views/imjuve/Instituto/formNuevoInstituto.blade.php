@extends('vendor.modal.template',['headerColor'=>'bg-earth'])

@section('title')<i class="fa fa-fw fa-user-plus"></i> {!! $title !!}@endsection

@section('content')
{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
    {{ Form::hidden('action',$action) }}
    <div class="row">
    <div class="col-md-6">
        {!! Field::text('organismo','',['label'=>'Nombre del organismo','addClass'=>'text-lowercase','autofocus','required']) !!}
        {!! Field::text('razon','',['label'=>'Razon social','placeholder'=>'Opcional','maxlength'=>10]) !!}
        {!! Field::text('telefono','',['label'=>'Telefono','placeholder'=>'Ej. +52 9831234567','required','maxlength'=>255]) !!}
        {!! Field::text('calle','',['label'=>'Calle(s)','required','maxlength'=>255]) !!}
        {!! Field::text('noext','',['label'=>'Numero exterior','required','maxlength'=>255]) !!}
        {!! Field::text('noint','',['label'=>'Numero interior','required','maxlength'=>255]) !!}

    </div>

    <div class="col-md-6">
        {!! Field::text('codigo_postal','',['label'=>'Codigo Postal','required','maxlength'=>255]) !!}
        {!! Field::select('Asentamiento','',['label'=>'Tipo de Asentamiento','required'],['Urbano'=>'Urbano','Rural'=>'Rural']) !!}
        {!! Field::select('estado','',['label'=>'Estado','required'],['Quintana Roo'=>'Quintana Roo','Yucatan'=>'Yucatan']) !!}
        {!! Field::select('municipio','',['label'=>'Municipio','required'],['Othon P. Blanco'=>'Othon P. Blanco','Benito Juarez'=>'Benito Juarez']) !!}
        {!! Field::select('localidad','',['label'=>'Localidad','required'],['Allende'=>'Allende','Limones'=>'Limones','Huay-Pix'=>'Huay-Pix','NicolasBravo'=>'Nicolas Bravo']) !!}
        {!! Field::select('vialidad','',['label'=>'Tipo de Vialidad','required'],['ampliación'=>'Ampliación','avenida'=>'Avenida','privada'=>'Privada','retorno'=>'Retorno','andador'=>'Andador']) !!}

    </div>
    
    </div>
{{ Form::close() }}
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
    var formUser = new AppForm;
	$.extend(formUser, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';

        this.start = function(){

            var self = this;

            // Prevent forms from submitting on enter key press
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
                organismo : { required : true, email : true, maxlength : 255 },
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
                    minlength : 'Mínimo {0} caracteresHello teacher, I am Daniel, I know you remind me, I am a little drunk but my sense of responsibility made me do this task right now.Hello teacher, I am Daniel, I know you remind me, I am a little drunk but my sense of responsibility made me do this task right now.',
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