'use strict';

var hPuesto;

App.loadScript('/js/helpers/helper.js', function(){
	hPuesto = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/catalogos/puestos/manager'
			},

			edit_ : function(id){
				this.edit({
					modal : 'form-puesto',
					url   : '/configuracion/catalogos/puestos/editar',
					id
				})
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar puesto'
				})
			}

		}
	}())
})