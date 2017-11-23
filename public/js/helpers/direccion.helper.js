'use strict';

var hTipoDocumento = new function(){

	this.new = function( id ){
		App.openModal({ url : '/configuracion/catalogos/tipos-documentos/nuevo', id })
	}

	this.edit = function( id ){
		App.openModal({
			id   : 'form-tipo-documento',
			url  : '/configuracion/catalogos/tipos-documentos/editar',
			data : { id }
		})
	}

	this.disable = function( id ){
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
	}

	this.delete = function( id ){
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

	this.reloadTable = function( table ){
		Codebase.blocks( $('div.block-mode-loading-refresh'), 'state_loading')
		App.reloadTable( table, function(){
			Codebase.blocks( $('div.block-mode-loading-refresh'), 'state_normal')
		})
	}

}