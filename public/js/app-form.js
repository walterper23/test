var AppForm = new function(){

	this.form;

	this.submit = function(form){
		var form = form || this.form
		form.submit()
	}

	this.formSubmit = function( form ){
		$(form).validate({
			ignore: [],
	        errorClass: 'invalid-feedback animated fadeInDown',
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

}