@extends('vendor.modal.template')

@section('title')<i class="fa fa-fw fa-sitemap"></i> {!! $title !!}@endsection

@section('content')
{!! Form::model($modelo,['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) !!}
	{!! Form::hidden('action',$action) !!}
	{!! Form::hidden('id',$id) !!}
    {!! Field::select('direccion',(optional($modelo) -> DEPA_DIRECCION),['label'=>'Direccion','required'],$direcciones) !!}
	{!! Field::text('nombre',(optional($modelo) -> DEPA_NOMBRE),['label'=>'Nombre','placeholder'=>'Nombre del departamento','autofocus','required']) !!}
{!! Form::close() !!}
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
	$.extend(new AppForm, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';

		this.rules = function(){
			return {
				direccion : { required : true },
				nombre : { required : true, maxlength : 255 }
			}
		};

		this.messages = function(){
			return {
				nombre : {
					required : 'Introduzca un nombre',
					maxlength : 'Máximo {0} caracteres'
				},
				direccion : { required : 'Especifique una dirección' }
			};
		};
	}).init();
</script>
@endpush