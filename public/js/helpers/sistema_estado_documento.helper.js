'use strict';

var hSistemaEstadoDocumento;

App.loadScript('/js/helpers/helper.js', function(){
	hSistemaEstadoDocumento = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/catalogos/estados-documentos/manager'
			},

			edit_ : function(id){
				this.edit({
					modal : 'form-estado-documento',
					url   : '/configuracion/catalogos/estados-documentos/editar',
					id
				})
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar estado de documento'
				})
			}
		}
	}())
})