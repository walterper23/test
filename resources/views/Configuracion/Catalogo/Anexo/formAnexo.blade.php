@extends('vendor.templateModal')

@section('title')<i class="fa fa-fw fa-clipboard"></i> {!! $title !!}@endsection

@section('content')
	@component('vendor.contentModal')
    {!! Form::model($modelo,['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) !!}
	    {!! Form::hidden('action',$action) !!}
	    {!! Form::hidden('id',$id) !!}
        {!! Field::text('nombre',(optional($modelo) -> ANEX_NOMBRE),['label'=>'Nombre','placeholder'=>'Nombre del anexo','autofocus']) !!}
	{!! Form::close() !!}
	@endcomponent
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
	var formAnexo = AppForm;
	$.extend(formAnexo, new function(){

		this.caller   = formAnexo;
		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{$form_id}}';

		this.rules = function(){
			return {
				nombre : { required : true, maxlength : 255 }
			}
		}

		this.messages = function(){
			return {
				nombre : { required : 'Introduzca un nombre' }
			}
		}
	}).init();
</script>
@endpush