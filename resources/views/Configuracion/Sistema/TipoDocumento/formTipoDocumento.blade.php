@extends('vendor.templateModal')

@section('title')<i class="fa fa-fw fa-files-o"></i> {!! $title !!} @endsection

@section('content')
	@component('vendor.contentModal')
    {{ Form::model($model,['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
        {{ Form::hidden('action',$action) }}
        {{ Form::hidden('id',$id) }}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="nombre">Nombre</label>
            <div class="col-sm-9">
            	{{ Form::text('nombre',(is_null($model) ? '' : $model->TIDO_NOMBRE_TIPO),['id'=>'nombre','class'=>'form-control','placeholder'=>'Nombre del tipo de documento','autofocus']) }}
            </div>
        </div>
	{{ Form::close() }}
	@endcomponent
@endsection

@push('js-custom')
<script type="text/javascript">
	
	$.extend(AppForm, new function(){

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
	}).init()

</script>
@endpush