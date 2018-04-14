/*
 *  Document   : op_auth_reminder.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Password Reminder Page
 */

var OpAuthReminder = function() {
    // Init Password Reminder Form Validation, for more examples you can check out https://github.com/jzaefferer/jquery-validation
    var initValidationReminder = function(){
        jQuery('.js-validation-reminder').validate({
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {
                jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            success: function(e) {
                jQuery(e).closest('.form-group').removeClass('is-invalid');
                jQuery(e).remove();
            },
            rules: {
                'username': {
                    required  : true,
                    minlength : 5,
                    email     : true
                }
            },
            messages: {
                'username': {
                    required  : 'Introduzca su nombre de usuario',
                    minlength : 'Introduzca mínimo {0} caracteres',
                    email     : 'Introduzca un correo electrónico válido'
                }
            }
        });
    };

    return {
        init: function () {
            // Init Password Reminder Form Validation
            initValidationReminder();
        }
    };
}();

// Initialize when page loads
jQuery(function(){ OpAuthReminder.init(); });