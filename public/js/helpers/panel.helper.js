'use strict';

var hPanel = function(){

	var url_manager = '/panel/documentos/manager';

	var _verAnexos = function(id){
		App.openModal({
			id : 'modal-anexos-documentos',
			size : 'modal-lg',
			url  : url_manager,
			data : { action : 1, seguimiento : id }
		});
	};

	var _verEscaneos = function(id){
		App.openModal({
			id : 'modal-escaneos-documentos',
			size : 'modal-lg',
			url  : url_manager,
			data : { action : 1, seguimiento : id }
		});
	};
	
	var _cambiarEstado = function(id){
		App.openModal({
			id : 'form-cambio-estado-documento',
			size : 'modal-lg',
			url  : url_manager,
			data : { action : 1, seguimiento : id }
		});
	};

	var _marcarImportante = function(element, id){
		App.ajaxRequest({
			url  : url_manager,
			data : { action : 3, documento : id },
			success : function(result){
				if ( result.status ){
					if ( result.importante ){
						$(element).find('i').removeClass('fa-star-o').addClass('fa-star').addClass('text-warning');
						AppAlert.notify({
							type : 'warning',
							message : result.message
						});
					}else{
						$(element).find('i').removeClass('fa-star').removeClass('text-warning').addClass('fa-star-o');
					}
				}else{

				}
			}
		});
	}

	return {
		verAnexos : function( id ){
			_verAnexos(id);
		},
		verEscaneos : function( id ){
			_verEscaneos(id);
		},
		cambiarEstado : function( id ){
			_cambiarEstado(id);
		},
		marcarImportante : function( element, id ){
			_marcarImportante(element, id);
		},

	};
}();