'use strict';

var hTipoDocumento;

App.loadScript('/js/helpers/helper.js', function(){
	hTipoDocumento = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/catalogos/tipos-documentos/manager'
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar tipo de documento'
				})
			},

			validate_ : function(id){
				this.active({
					data : { action : 5, id }
				})
			}

		}
	}())
})