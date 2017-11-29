'use strict';

var hUsuario;

App.loadScript('/js/helpers/helper.js', function(){
	hUsuario = $.extend({}, Helper, function(){
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