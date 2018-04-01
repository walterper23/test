'use strict';

var hPanel = function(){
	
	var _cambiarEstado = function(id){
		App.openModal({
			id : 'form-cambio-estado-documento',
			url : '/panel/documentos/manager',
			data : { action : 1, documento : id }
		});
	};

	return {
		cambiarEstado : function( id ){
			_cambiarEstado(id);
		},

	}
}();