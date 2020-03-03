'use strict';

var hAfiliado;

App.loadScript('/js/helpers/helper.js', function(){
	hAfiliado = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/imjuve/afiliacion/manager'
			},
			new_ : function(id, url){
				App.openModal({
					id    : 'form-nuevo-afiliado',
					size  : 'modal-lg',
					url   : '/imjuve/afiliacion/nuevo',
					data  : { id },
					btnCancel : true,
					btnOkText : 'Guardar'
				});
			},
			edit_ : function(id){
				App.openModal({
						id    : 'form-editar-afiliado',
						size  : 'modal-lg',
						url   : '/imjuve/afiliacion/editar',
						data  : { id },
						btnCancel : true,
						btnOkText : 'Editar'
				});

			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar afiliado'
				})
			},
		}
	}())
})