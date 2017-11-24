'use strict';

var hAnexo;

App.loadScript('/js/helpers/helper.js', function(){
	hAnexo = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/catalogos/anexos/manager'
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar anexo'
				})
			}
		}
	}())
})