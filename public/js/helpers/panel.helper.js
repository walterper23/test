'use strict';

var hPanel = function(){

	var manager = '/panel/documentos/manager';
	
	var _cambiarEstado = function(id){
		App.openModal({
			id : 'form-cambio-estado-documento',
			size : 'modal-lg',
			url : manager,
			data : { action : 1, seguimiento : id }
		});
	};

	var _marcarImportante = function(id){
		App.ajaxRequest({
			url  : manager,
			data : { action : 3, documento : id },
			success : function(result){
				if ( result.status )
				{
					location.reload();
				}
			}
		});
	}

	return {
		cambiarEstado : function( id ){
			_cambiarEstado(id);
		},

		marcarImportante : function( id ){
			_marcarImportante(id);
		},

	};
}();