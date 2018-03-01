@extends('vendor.templateModal')

@section('title') {!! $title !!} @endsection

@section('content')
<div class="block-content">
    {{ Form::model($modelo,['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
	    {{ Form::hidden('action',$action) }}
	    {{ Form::hidden('id',$id) }}
	    <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="direccion">Direcci&oacute;n</label>
            <div class="col-sm-9">
            	{{ Form::select('direccion',$direcciones,(is_null($modelo) ? '' : $modelo->ESDO_DIRECCION),['id'=>'direccion','class'=>'form-control','placeholder'=>'Seleccione una opción']) }}
            </div>
        </div>
        {!! Field::select('direccion',(is_null($modelo) ? '' : $modelo->ESDO_DIRECCION),['label'=>'Dirección','width'=>'col-md-9'],$direcciones) !!}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="departamento">Departamento</label>
            <div class="col-sm-9">
            	{{ Form::select('departamento',$departamentos,(is_null($modelo) ? '' : $modelo->ESDO_DEPARTAMENTO),['id'=>'departamento','class'=>'form-control','placeholder'=>'Seleccione una opción']) }}
            </div>
        </div> 
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="nombre">Nombre</label>
            <div class="col-sm-9">
            	{{ Form::text('nombre',(is_null($modelo) ? '' : $modelo->ESDO_NOMBRE),['id'=>'nombre','class'=>'form-control','placeholder'=>'Nombre del estado de documento']) }}
            </div>
        </div> 
	{{ Form::close() }}
</div>
@endsection

@push('js-custom')
<script type="text/javascript">
	
	$.extend(AppForm, new function(){

		this.context = $('#modal-{{ $form_id }}')
		this.form = $('#{{$form_id}}')
	
		this.submitHandler = function(form){
			if(!$(form).valid()) return false;
			App.ajaxRequest({
				url  : $(form).attr('action'),
				data : $(form).serialize(),
				before : function(){
					Codebase.blocks( AppForm.context.find('div.modal-content'), 'state_loading')
				},
				success : function(data){
					if( data.status ){
						AppForm.closeContext()

						if(data.tables != undefined){
							App.reloadTable(data.tables)
						}

						AppAlert.notify({
							type : 'info',
							message : data.message
						})
					}else{

						if( data.errors != undefined){
							$.each(data.errors,function(index, value){
								error = $('<div/>').addClass('invalid-feedback').attr('id',index+'-error').text(value[0]);
								$('#'+index).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
								$('#'+index).parents('.form-group > div').append(error);
							})
						}
						
					}
				},
				code422 : function(data){
					alert(JSON.stringify(data.responseJSON))
				}
			})
		}

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