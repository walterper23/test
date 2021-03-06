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
				App.openModal({
					id    : 'form-editar-instituto',
					size  : 'modal-lg',
					url   : '/imjuve/instituto/editar',
					data  : { id },
					btnCancel : true,
					btnOkText : 'Editar'
				});
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar usuario'
				})
			}

		

		}
	}())
})