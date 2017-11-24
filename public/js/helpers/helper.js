'use strict';

var Helper = function(){
		
	var _reload = function(table){
		Codebase.blocks($('div.block-mode-loading-refresh'), 'state_loading')
		App.reloadTable(table, function(){
			Codebase.blocks($('div.block-mode-loading-refresh'), 'state_normal')
		})
	}

	var _new = function( id, url ){
		App.openModal({ url, id })
	}

	var _edit = function(id, url, data){
		App.openModal({ id, url, data })
	}

	var _active = function(options){
		App.ajaxRequest({
			url : options.url,
			data : options.data || {},
			success : function(data){
				if(data.status){

					if(data.tables != undefined){
						App.reloadTables(data.tables)
					}
					
					AppAlert.notify({
						type : 'info',
						message : data.message
					})
				}else{
					AppAlert.notify({
						type : 'danger',
						message : data.message
					})
				}
			}
		})
	}

	var _delete = function(options){
		AppAlert.waiting({
			title    : options.title,
			text     : options.text,
			enterKey : options.enterKey || false,
			then     : options.then || function(){
				App.ajaxRequest({
					url : options.url || _manager(),
					data : options.data || {},
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
								type : 'danger',
								message : data.message
							})
						}
					}
				})
			}
		})
	}

	return {
		reload : function(table){
			_reload(table)
		},
		manager : function(){
			return '/'
		},
		new : function(id, url){
			_new(id, url)
		},
		edit : function(id, url, data){
			_edit(id, url, data)
		},
		active : function(options){
			_active({
				url  : options.url || this.manager(),
				data : options.data || { action : 3, id : options.id }
			})
		},
		delete : function(options){
			_delete({
				title : options.title || 'Eliminar',
				text  : options.text || 'La eliminación no se podrá deshacer',
				url   : options.url || this.manager(),
				data  : options.data || { action : 4, id : options.id }
			})
		},

	}
}()