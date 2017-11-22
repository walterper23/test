'use strict';

var App = function(){

	var defaultAjaxRequest = {
		data     : {},
		type     : 'POST',
		before   : function(){},
		success  : function(){},
		complete : function(){},
		error    : function(){},
		fail     : function(){},
	}

	var init = function(){
		configAjaxSetup()
	}

	var configAjaxSetup = function(){
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	}

	var ajaxRequest = function( options ){
		$.ajax($.extend({},defaultAjaxRequest,{
			url  : options.url,
			data : options.data,
			type : options.type,
			beforeSend : options.before,
			success : options.success,
			complete : options.complete,
			error : options.error,
			fail : options.fail
		}));
	}

	var defaultOpenModal = {
		id : 'modal-app',
		footer : true,
		btnOk : true,
		btnOkText : 'Aceptar',
		btnCancel : true,
		btnCancelText : 'Cancelar'
	}

	var openModal = function( config ){
		var config = $.extend({},defaultOpenModal,config)
		var modal = $('#modal-' + config.id )
		modal = modal.length > 0 ? modal : $('<div/>').addClass('modal fade').attr('id','modal-'+config.id).attr('role','dialog')

		var html = `<div class="modal-dialog">
		                <div class="modal-content">
		                	<div class="block block-themed block-transparent mb-0 block-mode-loading" style="min-height:120px;"></div>`

	    if( config.footer ){
	        html +=    `<div class="modal-footer">`
	        if( config.btnOk )     html += `<button type="button" class="btn btn-alt-primary" id="btn-ok">`+config.btnOkText+`</button>`
	        if( config.btnCancel ) html += `<button type="button" class="btn btn-alt-default" data="close-modal" id="btn-cancel">`+config.btnCancelText+`</button>`
	        html +=    `</div>`
	    }
	    
	    html +=     `</div>
	            </div>`
	    
		modal.html( html )

		$('body').append(modal)

		ajaxRequest({
			url  : config.url,
			data : config.data,
			before : function(){
				modal.modal({
					backdrop: 'static',
					show : true
				})
			},
			success : function(response){
				modal.find('div.block').html( response )
			},
			complete : function(){
				Codebase.blocks( modal.find('div.block'), 'state_normal')
			}
		})

	}

	var reloadTable = function(table){
		$('#'+table).dataTable().api().ajax.reload();
	}

	var reloadTables = function(tables){
		$.each(tables,function(index,value){
			reloadTable(value)
		})
	}

	return {
        init : function(){
            init()
        },
        ajaxRequest : function(options){
            ajaxRequest(options)
        },
        openModal : function(options){
            openModal(options)
        },
        reloadTable : function(table){
            reloadTable(table)
        },
        reloadTables : function(tables){
            reloadTables(tables)
        }
    }
}()

App.init()