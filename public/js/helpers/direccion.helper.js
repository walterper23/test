'use strict';

var hDireccion;

App.loadScript('/js/helpers/helper.js', function(){
	hDireccion = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/catalogos/direcciones/manager'
			},

			edit_ : function(id){
				this.edit({
					modal : 'form-direccion',
					url   : '/configuracion/catalogos/direcciones/editar',
					id
				})
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar dirección'
				})
			}

		}
	}())
})