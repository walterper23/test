@extends('vendor.templateModal')

@section('title')<i class="fa fa-fw fa-files-o"></i> {!! $title !!} @endsection

@section('content')
	@component('vendor.contentModal')
    {{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
	    {{ Form::hidden('action',$action) }}
	    {{ Form::hidden('id',$id) }}
		{!! Field::text('nombre',$model -> SYTD_NOMBRE_TIPO,['label'=>'Nombre','placeholder'=>'Nombre del tipo de documento','autofocus']) !!}
	{{ Form::close() }}
	@endcomponent
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
	$.extend(new AppForm, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';
			
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