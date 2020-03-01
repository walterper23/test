@extends('vendor.modal.template',['headerColor'=>'bg-earth'])

@section('title')<i class="fa fa-fw fa-user-plus"></i> {!! $title !!}@endsection

@section('content')
{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
    {{ Form::hidden('action',$action) }}
    <div class="row">
        <div class="col-md-12">       
            {!! Field::select('tipoActividad','',['label'=>'Tipo de Actividad','required'],$tiposActividades) !!}
        </div>
        <div class="col-md-6">    
            {!! Field::text('nombre','',['label'=>'Nombre','placeholder'=>'','required','maxlength'=>255]) !!} 
        </div>
        <div class="col-md-6">
            {!! Field::text('descripcion','',['label'=>'Descripción','placeholder'=>'','required','maxlength'=>255]) !!}   
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
                descripcion : { required : true, minlength : 3, maxlength : 255 },
                nombre : { required : true, minlength : 1, maxlength : 255 },
                tipoActividad : { required : true }
			}
		}

		this.messages = function(){
			return {
				
                descripcion : {
                    required : 'Introduzca la descripción del actividad',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                nombre : {
                    required : 'Introduzca el nombre de la actividad',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                
                tipoActividad : {
                    required : 'Seleccione un tipo de actividad'
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