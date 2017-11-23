'use strict';

var AppForm = new function(){

	this.context;
	this.form;

	this.init = function(){
		var self = this;
		this.btnOk = this.context.find('#btn-ok')
		this.btnCancel = this.context.find('#btn-cancel')
		this.btnClose = this.context.find('[data="close-modal"]')

		this.formSubmit(this.form)

		this.cloneForm = this.form.clone()

        this.btnOk.on('click', function(e){
        	self.submit()
        });

        this.btnClose.on('click', function(e){
        	self.onClose()
        })
	}

	this.submit = function(form){
		var form = form || this.form
		form.submit()
	}

	this.formSubmit = function( form ){
		$(form).validate({
			ignore: [],
	        errorClass: 'invalid-feedback',
	        errorElement: 'div',
	        errorPlacement: function(error, e) {
	            $(e).parents('.form-group > div').append(error);
	        },
	        highlight: function(e) {
	            $(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
	        },
	        success: function(e) {
	            $(e).closest('.form-group').removeClass('is-invalid');
	            $(e).remove();
	        },
			rules : this.rules(),
			messages : this.messages(),
			submitHandler: this.submitHandler
		})
	}

	this.rules = function(){
		return { }
	}

	this.messages = function(){
		return { }
	}

	this.submitHandler = function( form ){
		if(!$(form).valid()) return false
		return true
	}

	this.onClose = function(){
		if(	this.cloneForm.serialize() != this.form.serialize() ){
			AppAlert.confirm({
				title : 'Guardar cambios',
				text  : '¿Desea guardar los cambios realizados?',
				then  : function(){
					
				}
			});
		}else{
			this.closeContext()
		}
	}

	this.closeContext = function(){
		this.context.modal('hide')
	}

}