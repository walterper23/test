'use strict';

var hRecepcion;

App.loadScript('/js/helpers/helper.js', function(){
	hRecepcion = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/recepcion/documentos/manager'
			},

			anexos : function( id ){
				App.openModal({
					id    : 'anexos-escaneos',
					size  : 'modal-lg',
					url   : '/documento/local/anexos-escaneos',
					data  : { id },
					btnOk : false,
					btnCancelText : 'Cerrar'
				});
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
						location.href = '/recepcion/documentos/recepcionados'
					}
				})
			},

		}
	}())
})