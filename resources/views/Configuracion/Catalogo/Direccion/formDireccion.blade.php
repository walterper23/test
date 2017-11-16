<div class="block-header bg-primary-dark">
    <h3 class="block-title">{{ $title or '' }}</h3>
    <div class="block-options">
        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
            <i class="si si-close"></i>
        </button>
    </div>
</div>
<div class="block-content">
    {{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="nombre">Nombre</label>
            <div class="col-sm-9">
            	{{ Form::text('nombre',null,['id'=>'nombre','class'=>'form-control','placeholder'=>'Dirección','autofocus']) }}
            </div>
        </div>
	{{ Form::close() }}
</div>

<script type="text/javascript">
	
	$.extend(AppForm, new function(){

		this.init = function(){
			$this = this
			this.context = $('div.modal.fade#modal-{{ $form_id }}')
			this.form = this.context.find('form')
			this.btnOk = this.context.find('#btn-ok')
			this.btnCancel = this.context.find('#btn-cancel')

			this.formSubmit(this.form)

	        this.btnOk.on('click', function(e){
	        	$this.submit()
	        });
		}	
		
		this.submitHandler = function(form){
			if(!$(form).valid()) return false;
			App.ajaxRequest({
				url  : $(form).attr('action'),
				data : $(form).serialize(),
				before : function(){
					Codebase.blocks( AppForm.context.find('div.modal-content'), 'state_loading')
				},
				success : function(data){
					AppForm.btnCancel.click()
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