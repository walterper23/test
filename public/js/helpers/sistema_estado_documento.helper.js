'use strict';

var hSistemaEstadoDocumento;

App.loadScript('/js/helpers/helper.js', function(){
	hSistemaEstadoDocumento = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/sistema/estados-documentos/manager'
			},

			edit_ : function(id){
				this.edit({
					modal : 'form-estado-documento',
					url   : '/configuracion/sistema/estados-documentos/editar',
					id
				})
			}
		}
	}())
})