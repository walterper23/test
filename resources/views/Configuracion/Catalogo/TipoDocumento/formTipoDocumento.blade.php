<div class="block-header bg-primary-dark">
    <h3 class="block-title">{{ $title or '' }}</h3>
    <div class="block-options">
        <button type="button" class="btn-block-option" data="close-modal" aria-label="Close">
            <i class="si si-close"></i>
        </button>
    </div>
</div>
<div class="block-content">
    {{ Form::model($model,['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
        {{ Form::hidden('id',$id) }}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="nombre">Nombre</label>
            <div class="col-sm-9">
            	{{ Form::text('nombre',(is_null($model) ? '' : $model->TIDO_NOMBRE_TIPO),['id'=>'nombre','class'=>'form-control','placeholder'=>'Nombre del tipo de documento','autofocus']) }}
            </div>
        </div>
	{{ Form::close() }}
</div>

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
							App.reloadTables(data.tables)
						}

						AppAlert.notify({
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