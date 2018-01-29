<div class="block-header bg-primary-dark">
    <h3 class="block-title">{{ $title or '' }}</h3>
    <div class="block-options">
        <button type="button" class="btn-block-option" data="close-modal" aria-label="Close">
            <i class="si si-close"></i>
        </button>
    </div>
</div>
<div class="block-content">
    {{ Form::model($modelo,['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
        {{ Form::hidden('action',$action) }}	
		{{ Form::hidden('id',$id) }}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="direccion">Direcci&oacute;n</label>
            <div class="col-sm-9">
            	{{ Form::select('direccion',$direcciones,(is_null($modelo) ? '' : $modelo->SEGU_DIRECCION),['id'=>'direccion','class'=>'form-control','placeholder'=>'Seleccione una opción','autofocus']) }}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="departamento">Departamento</label>
            <div class="col-sm-9">
            	{{ Form::select('departamento',$departamentos,(is_null($modelo) ? '' : $modelo->SEGU_DEPARTAMENTO),['id'=>'departamento','class'=>'form-control','placeholder'=>'Seleccione una opción']) }}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="estado">Estado de Documento</label>
            <div class="col-sm-9">
            	{{ Form::select('estado',$estados,(is_null($modelo) ? '' : $modelo->SEGU_ESTADO_DOCUMENTO),['id'=>'estado','class'=>'form-control','placeholder'=>'Seleccione una opción']) }}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="estado">Observaci&oacute;n</label>
            <div class="col-sm-9">
            	{{ Form::textarea('observacion',(is_null($modelo) ? '' : $modelo->SEGU_OBSERVACION),['id'=>'observacion','size'=>'20x5','class'=>'form-control','placeholder'=>'Opcional']) }}
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
				nombre : { required : true, minlength : 3, maxlength : 255 },
				direccion : { required : true },
				departamento : { required : true }
			}
		}

		this.messages = function(){
			return {
				nombre : {
					required  : 'Introduzca un nombre',
					minlength : 'Introduzca mínimo {0} carácteres',
				},
				direccion : { required : 'Especifique una dirección' },
				departamento : { required : 'Especifique un departamento' }
			}
		}
	}).init()

</script>