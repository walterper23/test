@extends('vendor.modal.template')

@section('title')<i class="fa fa-fw fa-clipboard"></i> {!! $title !!}@endsection

@section('content')
    {!! Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) !!}
	    {!! Form::hidden('action',$action) !!}
	    {!! Form::hidden('id',$id) !!}
	    {!! Form::hidden('recepcion',$recepcion) !!}
        {!! Field::text('nombre',(optional($modelo) -> getNombre()),['label'=>'Nombre','placeholder'=>'Nombre del anexo','required','autofocus']) !!}
	{!! Form::close() !!}
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
	var formAnexo = new AppForm;
	$.extend(formAnexo, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{$form_id}}';

		this.rules = function(){
			return {
				nombre : { required : true, minlength : 1, maxlength : 255 }
			}
		}

		@if( $recepcion )
		this.successSubmitHandler = function( result ){
            if( result.status ){
                formAnexo.closeContext().displayMessage(result);
                var option = $('<option>', { value : result.anexo.id, text : result.anexo.nombre} );
                $('#anexo').append( option ).val('');
            }
        };
        @endif

		this.messages = function(){
			return {
				nombre : {
					required  : 'Introduzca un nombre',
					minlength : 'Mínimo {0} caracteres',
					maxlength : 'Máximo {0} caracteres'
				}
			}
		}
	}).init();
</script>
@endpush