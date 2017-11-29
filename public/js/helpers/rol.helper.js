'use strict';

var hRol;

App.loadScript('/js/helpers/helper.js', function(){
	hRol = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/usuarios/manager'
			},

			edit_ : function(id){
				this.edit({
					modal : 'form-puesto',
					url   : '/configuracion/usuarios/editar',
					id
				})
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar rol'
				})
			}

		}
	}())
})