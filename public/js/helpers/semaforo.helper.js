'use strict';

var hSemaforo;

App.loadScript('/js/helpers/helper.js', function(){
	hSemaforo = $.extend({}, Helper, function(){
		return {
			verSeguimiento : function(type, id){
				App.openModal({
					id    : 'ver-seguimiento',
					size  : 'modal-lg',
					url   : '/panel/documentos/semaforizados/seguimiento',
					data  : { type, id },
					btnOk : false,
					btnCancelText : 'Cerrar'
				});
			}
		}
	}());
})