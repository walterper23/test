'use strict';

var hUsuario;

App.loadScript('/js/helpers/helper.js', function(){
	hUsuario = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/usuarios/manager'
			},

			new_ : function(id, url){
				App.openModal({
					id, url
				});
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
					id, title : 'Eliminar usuario'
				})
			},

			password_ : function(id){
				this.active({
					data : { action : 5, id }
				})
			}

		}
	}())
})