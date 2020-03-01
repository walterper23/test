'use strict';

var hUsuario;

App.loadScript('/js/helpers/helper.js', function(){
	hUsuario = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/imjuve/actividades/manager'
			},

			new_ : function(id, url){
				App.openModal({
					id, url, size : 'modal-lg'
				});
			},

			edit_ : function(id){
				this.edit({
					modal : 'form-editar-actividad',
					url   : '/imjuve/actividades/editar',
					id
				})
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar usuario'
				})
			},

			password : function(id){
				this.edit({
					modal : 'form-password',
					url   : '/configuracion/usuarios/password',
					id
				})
			}

		}
	}())
})