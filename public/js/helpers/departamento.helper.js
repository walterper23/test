'use strict';

var hDepartamento;

App.loadScript('/js/helpers/helper.js', function(){
	hDepartamento = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/catalogos/departamentos/manager'
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar departamento'
				})
			}
		}
	}())
})