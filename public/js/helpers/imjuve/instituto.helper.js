'use strict';

var hInstituto;

App.loadScript('/js/helpers/helper.js', function(){
	hInstituto = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/usuarios/manager'
			},

			new_ : function(id, url){
				App.openModal({
					id, url, size : 'modal-lg'
				});
			},

			edit_ : function(id){
				this.edit({
					modal : 'form-editar-usuario',
					url   : '/configuracion/usuarios/editar',
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