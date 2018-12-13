'use strict';

var hRecepcion;

App.loadScript('/js/helpers/helper.js', function(){
	hRecepcion = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/recepcion/documentos/manager'
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
					url   : '/documento/local/anexos-escaneos',
					data  : { id },
					btnOk : false,
					btnCancelText : 'Cerrar'
				});
			},

			reloadTables : function( tables ){
				App.reloadTable(tables[0], function(){
					App.reloadTable(tables[1], function(){
						App.reloadTable(tables[2], function(){
							Codebase.blocks($('div.block-mode-loading-refresh'), 'state_normal')
						})
					})
				})
			},

			cancelar : function( type = 1 ){
				let msg = type == 1 ? 'recepción' : 'modificación';
				AppAlert.confirm({
					title : 'Cancelar ' + msg,
					text  : '¿Está seguro que desea cancelar la ' + msg + '?',
					cancelBtnText : 'Regresar',
					then : function(){
						console.log(document.referrer)
						location.href = document.referrer
					}
				})
			},

			delete_ : function(id){
				this.delete({
					title : 'Eliminar recepción',
					data : { action : 4, id }
				})
			}

		}
	}())
})