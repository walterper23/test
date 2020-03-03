@extends('vendor.modal.template',['headerColor'=>'bg-earth'])

@section('title')<i class="fa fa-fw fa-edit"></i> {!! $title !!}@endsection

@section('content')
    {{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
        {{ Form::hidden('action',$action) }}
        {{ Form::hidden('id',$id) }}
        {!! Field::text('organismo',$modelo->getAlias(),['label'=>'Nombre del organismo','placeholder'=>'Opcional','maxlength'=>10]) !!}
        {!! Field::text('razon',$modelo->getRazonSocial(),['label'=>'Razon social','placeholder'=>'Ej. Director de Operaciones','required','maxlength'=>255]) !!}
        {!! Field::text('telefono',$modelo->getTelefono(),['label'=>'Teléfono','placeholder'=>'Opcional','maxlength'=>25]) !!}
        {!! Field::text('cp',$modelo->Direccion->getCp(),['label'=>'cp','placeholder'=>'Opcional','maxlength'=>25]) !!}

    {{ Form::close() }}
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
    var formEditUser = new AppForm;
	$.extend(formEditUser, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';

        this.start = function(){
            
        };
	
		this.rules = function(){
			return {
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

	}).init().start();

</script>
@endpush