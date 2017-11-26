'use strict';

var hDireccion;

App.loadScript('/js/helpers/helper.js', function(){
	hDireccion = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/catalogos/direcciones/manager'
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar dirección'
				})
			}

		}
	}())
})