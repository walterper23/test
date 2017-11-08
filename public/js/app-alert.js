var AppAlert = new function(){
	
    this.success = function( options ){
        swal({
            type: options.type || 'success',
            title: options.title || '',
            text: options.text || '',
            html: options.html || '',
            confirmButtonText: options.okBtnText || 'Aceptar',
            confirmButtonClass: options.okBtnClass || 'btn btn-primary',
            timer: options.timer
        })
    }

	this.confirm = function( options ){
		swal({
            type: options.type || 'warning',
            title: options.title || '',
            text: options.text || '',
            html: options.html || '',
            focusConfirm: options.focusConfirm || false,
            showCancelButton: true,
            confirmButtonText: options.okBtnText || 'Aceptar',
            confirmButtonClass: options.okBtnClass || 'btn btn-primary',
            cancelButtonText: options.cancelBtnText || 'Cancelar',
            cancelButtonClass: options.cancelBtnClass || 'btn btn-default',
            preConfirm: options.preConfirm || function(){}
        }).then(function(result){
            if( options.then != undefined ){ options.then(result) }
        },function(dismiss){
            if( options.dismiss != undefined ) options.dismiss(dismiss)
        });
	}

    this.waiting = function( options ){
        swal({
            type : options.type || 'warning',

            preConfirm: function() {
                return new Promise(function (resolve) {
                    setTimeout(function () {
                        resolve();
                    }, 50);
                });
            }
        })
    }

    this.error = function( options ){
        swal({
            type : options.type || 'error'  
        })
    }

}