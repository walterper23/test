'use strict';

var hRecepcion;

App.loadScript('/js/helpers/helper.js', function(){
	hRecepcion = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/recepcion/documentos/manager'
			},

			verAcuse : function( id ){
				console.log(id)
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
						history.back()
					}
				})
			},

		}
	}())
})