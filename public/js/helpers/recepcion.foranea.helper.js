'use strict';

var hRecepcionForanea;

App.loadScript('/js/helpers/helper.js', function(){
	hRecepcionForanea = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/recepcion/documentos-foraneos/manager'
			},

			nuevoAnexo : function(id, url){
				App.openModal({
					id   : id,
					url  : url,
					data : { recepcion : true }
				});
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

			enviar : function( id ){
				AppAlert.confirm({
					title : 'Enviar documento',
					text  : '¿Está seguro que desea enviar el documento a Oficialía de Partes en Chetumal?',
					cancelBtnText : 'Regresar',
					then : function(){
						hRecepcionForanea.active({
							data : { action : 5, id }
						});
					}
				})
			},

			reloadTables : function( tables ){
				console.log(tables[0])
				App.reloadTable(tables[0], function(){
					App.reloadTable(tables[1], function(){
						App.reloadTable(tables[2], function(){
							Codebase.blocks($('div.block-mode-loading-refresh'), 'state_normal')
						})
					})
				})
			},

			cancelar : function(){
				AppAlert.confirm({
					title : 'Cancelar recepción',
					text  : '¿Está seguro que desea cancelar la recepción?',
					cancelBtnText : 'Regresar',
					then : function(){
						location.href = '/recepcion/documentos-foraneos/recepcionados?view=denuncias'
					}
				})
			},

		}
	}())
})