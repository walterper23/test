@extends('vendor.templateModal')

@section('title')<i class="fa fa-tags"></i> {!! $title !!}@endsection

@section('content')
	@component('vendor.contentModal')
    {!! Form::model($modelo,['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) !!}
	    {!! Form::hidden('action',$action) !!}
	    {!! Form::hidden('id',$id) !!}
        {!! Field::selectTwo('direccion',(is_null($modelo) ? '' : $modelo->ESDO_DIRECCION),['label'=>'Dirección'],$direcciones) !!}
        {!! Field::selectTwo('departamento',(is_null($modelo) ? '' : $modelo->ESDO_DEPARTAMENTO),['label'=>'Departamento'],$departamentos) !!}
        {!! Field::text('nombre',(is_null($modelo) ? '' : $modelo->ESDO_NOMBRE),['label'=>'Nombre','placeholder'=>'Nombre del estado de documento']) !!}
	{!! Form::close() !!}
	@endcomponent
@endsection

@push('js-custom')
<script type="text/javascript">
	
	$.extend(AppForm, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{$form_id}}';
	
		this.rules = function(){
			return {
				direccion : { required : true },
				departamento : { required : true },
				nombre : { required : true, maxlength : 255 }
			}
		}

		this.messages = function(){
			return {
				direccion : { required : 'Especifique una dirección' },
				departamento : { required : 'Especifique un departamento' },
				nombre : { required : 'Introduzca un nombre' }
			}
		}
	}).init()

</script>
@endpush