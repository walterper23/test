'use strict';

var hSistemaTipoDocumento;

App.loadScript('/js/helpers/helper.js', function(){
	hSistemaTipoDocumento = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/sistema/tipos-documentos/manager'
			},

			edit_ : function(id){
				this.edit({
					modal : 'form-tipo-documento',
					url   : '/configuracion/sistema/tipos-documentos/editar',
					id
				})
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar tipo de documento'
				})
			}
		};
	}());
});