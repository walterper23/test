'use strict';

var hRecepcion;

App.loadScript('/js/helpers/helper.js', function(){
	hRecepcion = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/catalogos/puestos/manager'
			},

			cancelar : function(){
				AppAlert.confirm({
					title : 'Cancelar recepción',
					text  : '¿Está seguro que desea cancelar la recepción?',
					cancelBtnText : 'Regresar',
					then : function(){
						history.back()
					}
				})
			},

			edit_ : function(id){
				this.edit({
					modal : 'form-puesto',
					url   : '/configuracion/catalogos/puestos/editar',
					id
				})
			},

			delete_ : function(id){
				this.delete({
					id, title : 'Eliminar puesto'
				})
			}

		}
	}())
})