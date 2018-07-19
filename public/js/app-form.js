'use strict';

var AppForm = function(){
	var self;
	this.context_   = 'body';
	this.form_ 	    = 'form';
	this.btnOk_     = '#modal-btn-ok';
	this.btnCancel_ = '#modal-btn-cancel';
	this.btnClose_  = '[data-close="modal"]';

	this.init = function(){

		self = this;
		this.context   = $( this.context_ );
		this.form 	   = $( this.form_ );
		this.btnOk     = this.context.find( this.btnOk_ );
		this.btnCancel = this.context.find( this.btnCancel_ );
		this.btnClose  = this.context.find( this.btnClose_ );

		this.formSubmit(this.form)

		this.cloneForm = this.form.clone()

        this.btnOk.on('click', function(e){
        	e.preventDefault();
        	self.submit()
        });

        this.btnClose.on('click', function(e){
        	self.onClose()
        })
        
        return this;
	};

	this.formSubmit = function( form ){
		$(form).validate({
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
			submitHandler : this.submitHandler
		});
	};

	this.submit = function( form ){
		var form = form || this.form;
		form.submit();
	};

	this.rules = function(){
		return { }
	};

	this.messages = function(){
		return { }
	};

	this.resetForm = function(){
		this.cloneForm = this.form.trigger('reset').clone()
		return this;
	};

	this.submitHandler = function( form ){
		if(!$(form).valid()) return false;

		App.ajaxRequest({
			url        : $(form).attr('action'),
			data       : $(form).serialize(),
			beforeSend : self.beforeSubmitHandler,
			success    : self.successSubmitHandler,
			code422    : self.displayErrors
		});
	};

	this.beforeSubmitHandler = function(){
		Codebase.blocks( self.context.find('div.modal-content > div.block'), 'state_loading');
	};

	this.successSubmitHandler = function( result ){
		if( result.status ){
			self.closeContext().reloadTables(result).displayMessage(result);
		}else{
			
		}
	};

	this.reloadTables = function( result ){
		if(result.tables != undefined){
			App.reloadTable(result.tables)
		}
		return this;
	};

	this.displayMessage = function( result ){
		if( result.message != undefined ){
			AppAlert.notify({
				icon : result.icon,
				type : result.type,
				message : result.message
			})
		}
		return this;
	}

	this.displayErrors = function( result ){

		Codebase.blocks( self.context.find('div.modal-content > div.block'), 'state_normal');
		
		if( result.responseJSON.errors != undefined ){
			$.each(result.responseJSON.errors, self.displayError);
		}
	};

	this.displayError = function( index, value ){
		var error = $('<div/>').addClass('invalid-feedback').attr('id',index+'-error').text(value[0]);
		$('#'+index).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
		$('#'+index).parents('.form-group > div').append(error);
	};

	this.onClose = function(){
		if(	self.cloneForm.serialize() != self.form.serialize() ){
			AppAlert.confirm({
				title : 'Descartar cambios',
				text  : '¿Desea descartar los cambios realizados?',
				okBtnText : 'Descartar',
				cancelBtnText : 'Regresar',
				then  : function(){
					self.resetForm().closeContext();
				}
			});
		}else{
			self.resetForm().closeContext();
		}
	};

	this.closeContext = function(){
		self.context.modal('hide');
		return this;
	};
}