'use strict';

var AppAlert = function(){

    var defaults = {
        title          : '',
        text           : '',
        html           : '',
        okBtnText      : 'Aceptar',
        okBtnClass     : 'btn btn-primary',
        cancelBtnText  : 'Cancelar',
        cancelBtnClass : 'btn btn-default ml-20',
        btnStyling     : false,
        outClick     : false,
        outEsc       : false,
        enterKey     : true,
        showLoader   : true,
        focusConfirm : true,
        timer        : null,
        preConfirm : function(){ return new Promise(function(resolve){ resolve() }) },
        then       : function(result){ },
        dismiss    : function(dismiss){ }
    }
	
    var success = function( options ){
        swal({
            type  : options.type || 'success',
            title : options.title,
            text  : options.text,
            html  : options.html,
            focusConfirm: options.focusConfirm,
            confirmButtonText: options.okBtnText,
            confirmButtonClass: options.okBtnClass,
            buttonsStyling: options.btnStyling,
            timer: options.timer
        }).then(options.then)
    }

    var preConfirm = function( options ){
        confirm = swal({
            type  : options.type || 'warning',
            title : options.title,
            text  : options.text,
            html  : options.html,
            focusConfirm: options.focusConfirm,
            showCancelButton: true,
            confirmButtonText: options.okBtnText,
            confirmButtonClass: options.okBtnClass,
            cancelButtonText: options.cancelBtnText,
            cancelButtonClass: options.cancelBtnClass,
            buttonsStyling: options.btnStyling,
            preConfirm: options.preConfirm
        }).then(options.then, options.dismiss)
    }

	var confirm = function( options ){
		var confirm = swal({
            type  : options.type || 'question',
            title : options.title,
            text  : options.text,
            html  : options.html,
            focusConfirm: options.focusConfirm,
            showCancelButton: true,
            confirmButtonText: options.okBtnText,
            confirmButtonClass: options.okBtnClass,
            cancelButtonText: options.cancelBtnText,
            cancelButtonClass: options.cancelBtnClass,
            buttonsStyling: options.btnStyling,
        }).then( options.then, options.dismiss )
	}

    var waiting = function( options ){
        swal({
            type  : options.type || 'warning',
            title : options.title,
            text  : options.text,
            html  : options.html,
            focusConfirm: options.focusConfirm,
            showCancelButton: true,
            confirmButtonText: options.okBtnText,
            confirmButtonClass: options.okBtnClass,
            cancelButtonText: options.cancelBtnText,
            cancelButtonClass: options.cancelBtnClass,
            buttonsStyling: options.btnStyling,
            allowOutsideClick: options.outClick,
            allowEscapeKey: options.outEsc,
            allowEnterKey: options.enterKey,
            showLoaderOnConfirm: options.showLoader,
            preConfirm: options.preConfirm
        }).then( options.then, options.dismiss )
    }

    var error = function( options ){
        swal({
            type  : options.type || 'error',
            title : options.title,
            text  : options.text,
            html  : options.html,
            focusConfirm: options.focusConfirm,
            confirmButtonText: options.okBtnText,
            confirmButtonClass: options.okBtnClass,
            buttonsStyling: options.btnStyling,
            allowOutsideClick: options.outClick,
            allowEscapeKey: options.outEsc,
            allowEnterKey: options.enterKey,
            preConfirm: options.preConfirm
        }).then( options.then )
    }

    var defaultsNotify = {
        icon    : '',
        type    : 'success',
        url     : '',
        element : 'body',
        dismiss         : true,
        newest_on_top   : false,
        showProgressbar : false,
        from    : 'bottom',
        align   : 'right',
        offset  : 20,
        spacing : 10,
        z_index : 1033,
        delay   : 3000,
        timer   : 300,

    }

    var notify = function( options ){
        $.notify({
            icon    : options.icon,
            message : options.message,
            url     : options.url
        },
        {
            element       : options.element,
            type          : options.type,
            allow_dismiss : options.dismiss,
            placement : {
                from  : options.from,
                align : options.align
            }
        });
    }

    return {
        success : function(options){
            success($.extend({}, defaults, options))
        },
        preConfirm : function(options){
            preConfirm($.extend({}, defaults, options))
        },
        confirm : function(options){
            confirm($.extend({}, defaults, options))
        },
        waiting : function(options){
            waiting($.extend({}, defaults, options))
        },
        error : function(options){
            error($.extend({}, defaults, options))
        },
        notify : function(options){
            notify($.extend({}, defaultsNotify, options))
        }
    }

}();