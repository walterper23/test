'use strict';

var hDepartamento;

App.loadScript('/js/helpers/helper.js', function(){
	hDepartamento = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/catalogos/departamentos/manager'
			},

			edit_ : function(id){
				this.edit({
					modal : 'form-departamento',
					url   : '/configuracion/catalogos/departamentos/editar',
					id
				})
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar departamento'
				})
			}
		}
	}())
})