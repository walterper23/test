@extends('vendor.templateModal')

@section('title') <i class="fa fa-fw fa-user-plus"></i> Nuevo usuario @endsection

@section('content')
    {{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
	    {{ Form::hidden('action',1) }}
	    {!! Field::text('usuario','',['label'=>'Usuario','placeholder'=>'Nombre de usuario','autofocus']) !!}
	    {!! Field::password('password','',['label'=>'Contraseña']) !!}
	    {!! Field::password('password_confirmation','',['label'=>'Contraseña','placeholder'=>'Confirme la contraseña']) !!}
	    {!! Field::text('descripcion','',['label'=>'Descripción','placeholder'=>'Ej. Director de Operaciones y Vigilancia']) !!}
	    {!! Field::text('nombres','',['label'=>'Nombre(s)','placeholder'=>'Nombre(s)',]) !!}
	    {!! Field::text('apellidos','',['label'=>'Apellido(s)','placeholder'=>'Apelido paterno y materno']) !!}
	    {!! Field::email('email','',['label'=>'E-mail','placeholder'=>'Correo electrónico']) !!}
	    {!! Field::text('teléfono','',['label'=>'Teléfono','placeholder'=>'Opcional']) !!}
	{{ Form::close() }}
@endsection

@push('js-custom')
<script type="text/javascript">
	
	$.extend(AppForm, new function(){

		this.context = $('#modal-{{ $form_id }}')
		this.form = $('#{{$form_id}}')
	
		this.submitHandler = function(form){
			console.log(form)
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
				}
			})
		}


		this.rules = function(){
			return {
				usuario : { required : true, maxlength : 255 }
			}
		}

		this.messages = function(){
			return {
				usuario : { required : 'Introduzca un nombre usuario' }
			}
		}
	}).init()

</script>
@endpush