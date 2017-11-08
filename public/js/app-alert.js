'use strict';

var AppAlert = function(){

    var defaults = {
        title          : '',
        text           : '',
        html           : '',
        okBtnText      : 'Aceptar',
        okBtnClass     : 'btn btn-primary',
        cancelBtnText  : 'Cancelar',
        cancelBtnClass : 'btn btn-default',
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
            timer: options.timer
        }).catch(swal.noop)
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
        }).then( options.then )
    }

    return {
        success : function(options){
            $.extend(defaults, options)
            success(defaults)
        },
        preConfirm : function(options){
            $.extend(defaults, options)
            preConfirm(defaults)
        },
        confirm : function(options){
            $.extend({}, defaults, options)
            confirm(defaults)
        },
        waiting : function(options){
            $.extend({}, defaults, options)
            waiting(defaults)
        },
        error : function(options){
            $.extend(defaults, options)
            error(defaults)
        }
    }

}();