'use strict';

var hTipoDocumento;

App.loadScript('/js/helpers/helper.js', function(){
	hTipoDocumento = $.extend({}, Helper, function(){
		return {
			disable : function( id ){
				App.ajaxRequest({
					url : '/configuracion/catalogos/tipos-documentos/post-desactivar',
					data : { id },
					success : function(data){
						if(data.status){

							if(data.tables != undefined){
								App.reloadTables(data.tables)
							}
							
							AppAlert.notify({
								message : data.message
							})
						}else{
							AppAlert.notify({
								type : 'error',
								message : data.message
							})
						}
					}
				});
			},

			delete : function( id ){
				AppAlert.waiting({
					title : 'Eliminar tipo de documento',
					text  : 'La eliminación no se podrá deshacer',
					enterKey: false,
					then : function(){
						App.ajaxRequest({
							url : '/configuracion/catalogos/tipos-documentos/post-eliminar',
							data : { id },
							success : function(data){
								if(data.status){

									if(data.tables != undefined){
										App.reloadTable(data.tables)
									}

									AppAlert.notify({
										message : data.message
									})
								}else{
									AppAlert.notify({
										type : 'error',
										message : data.message
									})
								}
							}
						});
					}
				});
			}
		}
	}())
	console.log(hTipoDocumento)
})