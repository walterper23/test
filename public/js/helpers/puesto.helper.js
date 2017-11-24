'use strict';

var hPuesto;

App.loadScript('/js/helpers/helper.js', function(){
	hPuesto = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/catalogos/puestos/manager'
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar puesto'
				})
			}

		}
	}())
})