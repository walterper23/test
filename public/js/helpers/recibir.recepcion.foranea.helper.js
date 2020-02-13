'use strict';

var hRecibirRecepcionForanea;

App.loadScript('/js/helpers/helper.js', function(){
	hRecibirRecepcionForanea = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/recepcion/documentos/foraneos/manager'
			},

			anexos : function( id ){
				App.openModal({
					id    : 'anexos-escaneos',
					size  : 'modal-lg',
					url   : '/documento/foraneo/anexos-escaneos',
					data  : { id },
					btnOk : false,
					btnCancelText : 'Cerrar'
				});
			},

			recibir : function( id ){
				AppAlert.confirm({
					title : 'Recibir documento',
					text  : '¿Desea marcar el documento foráneo como recibido?',
					cancelBtnText : 'Regresar',
					then : function(){
						hRecibirRecepcionForanea.active({
							data : { action : 1, id }
						});
					}
				})
			},

			validar : function( id ){
				AppAlert.confirm({
					title : 'Validar documento',
					text  : '¿Desea marcar el documento foráneo como validado?',
					cancelBtnText : 'Regresar',
					then : function(){
						hRecibirRecepcionForanea.active({
							data : { action : 2, id }
						});
					}
				})
			},

			recepcionar : function( id ){
				AppAlert.confirm({
					title : 'Recepcionar documento',
					text  : '¿Desea terminar de recepcionar el documento foráneo?',
					cancelBtnText : 'Regresar',
					then : function(){
						hRecibirRecepcionForanea.active({
							data : { action : 3, id }
						});
					}
				})
			},
		}
	}());
});