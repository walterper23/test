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
					id, url, btnOk : false
				});
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