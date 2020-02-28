'use strict';

var hInstituto;

App.loadScript('/js/helpers/helper.js', function(){
	hInstituto = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/imjuve/instituto/manager'
			},

			new_ : function(id, url){
				App.openModal({
					id, url, size : 'modal-lg'
				});
			},

			edit_ : function(id){
				this.edit({
					modal : 'form-editar-instituto',
					url   : '/imjuve/instituto/editar',
					id
				})
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar usuario'
				})
			}

		

		}
	}())
})