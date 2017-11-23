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

	var _disable = function(){

	}

	var _delete = function(){

	}

	return {
		reload : function(table){
			_reload(table)
		},
		new : function(id, url){
			_new(id, url)
		},
		edit : function(id, url, data){
			_edit(id, url, data)
		},
		disable : function(id){
			_disable(url, id)
		},
		delete : function(id){
			_delete(url, id)
		},

	}
}()