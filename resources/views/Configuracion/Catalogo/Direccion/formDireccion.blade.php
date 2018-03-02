@extends('vendor.templateModal')

@section('title') {!! $title !!} @endsection

@section('content')
{!! Form::model($modelo,['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) !!}
	{!! Form::hidden('action',$action) !!}
	{!! Form::hidden('id',$id) !!}
	{!! Field::text('nombre',(is_null($modelo) ? '' : $modelo->DIRE_NOMBRE),['label'=>'Nombre','placeholder'=>'Nombre de la dirección','autofocus']) !!}
{!! Form::close() !!}
@endsection

@push('js-custom')
<script type="text/javascript">
	
	$.extend(AppForm, new function(){

		this.context = $('#modal-{{ $form_id }}')
		this.form = $('#{{ $form_id }}')	
		
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
								console.log(error)
								console.log($('#'+index))
								$('#'+index).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
								$('#'+index).parents('.form-group > div').append(error);
							})
						}


						
					}
				}
			})
		}

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