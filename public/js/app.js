'use strict';

var App = function(){

	var _defaultAjaxRequest = {
		type        : 'POST',
		data        : {},
		processData : true,
		cache       : true,
		contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
		beforeSend  : function(){},
		success     : function(){},
		complete    : function(){},
		fail        : function(){},
		statusCode  : {
			422 : function( result ){
				console.log(result.responseJSON)
			}
		}
	};

	var _init = function(){
		_configAjaxSetup()
	};

	var _configAjaxSetup = function(){
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			error : function(x, status, error){
				console.log(x.status, status, error)
				if(x.status == 403){
					AppAlert.error({
						title : '¡ Acceso no autorizado !',
						text : 'Lo sentimos, no tienes permisos',
						then : function(){
							location.href = '/';
						}
					});
				}
			}
		});
	};

	var _ajaxRequest = function( options ){
		$.ajax($.extend({},_defaultAjaxRequest,{
			url         : options.url,
			type        : options.type,
			data        : options.data,
			processData : options.processData,
			cache       : options.cache,
			contentType : options.contentType,
			beforeSend  : options.beforeSend,
			success     : options.success,
			complete    : options.complete,
			error       : options.error,
			fail        : options.fail,
			statusCode : {
				403 : options.code403,
				422 : options.code422
			},
		}));
	};

	var _defaultOpenModal = {
		id : 'app',
		size : 'modal-md',
		footer : true,
		btnOk : true,
		btnOkText : 'Aceptar',
		btnCancel : true,
		btnCancelText : 'Cancelar'
	};

	var _openModal = function( config_modal ){
		var config = $.extend({},_defaultOpenModal,config_modal);
		var modal = $('#modal-' + config.id );
		modal = modal.length > 0 ? modal : $('<div/>').addClass('modal fade').attr('id','modal-'+config.id).attr('role','dialog');

		var html = `<div class="modal-dialog `+config.size+`">
		                <div class="modal-content">
		                	<div class="block block-themed block-transparent mb-0 block-mode-loading" style="min-height:120px;"></div>`;

	    if( config.footer ){
	        html +=    `<div class="modal-footer">`;
	        if( config.btnOk )     html += `<button type="button" class="btn btn-alt-primary" id="modal-btn-ok">`+config.btnOkText+`</button>`;
	        if( config.btnCancel ) html += `<button type="button" class="btn btn-alt-default" data-close="modal" id="modal-btn-cancel">`+config.btnCancelText+`</button>`;
	        html +=    `</div>`;
	    }
	    
	    html +=     `</div>
	            </div>`;
	    
		modal.html( html );

		$('body').append(modal);

		_ajaxRequest({
			url  : config.url,
			data : config.data,
			beforeSend : function(){
				modal.modal({
					backdrop : 'static',
					keyboard : false,
					show : true
				})
			},
			success : function(response){
				modal.find('div.block').html( response );
			},
			complete : function(){
				Codebase.blocks( modal.find('div.block'), 'state_normal');
			}
		});

	};

	var _reloadTable = function(table, callback, resetPaging){
		$('#'+table).dataTable().api().ajax.reload(callback, resetPaging);
	};

	var _loadScript = function(url, callback){
		$.getScript({
			url   : url,
			cache : true
		}, callback)
	};

	return {
        init : function(){
            _init()
        },
        ajaxRequest : function(options){
            _ajaxRequest(options);
        },
        openModal : function(options){
            _openModal(options);
        },
        reloadTable : function(table, callback = null , resetPaging = false){
			if(typeof table == 'string' && table.length){
            	_reloadTable(table, callback, resetPaging);
        	}else if(table instanceof Array){
				var callback = table[1] || null;
				var resetPaging = table[2] || false;
				_reloadTable(table[0],callback,resetPaging);
			}
        },
        reloadTables : function(tables){
            for(var index in tables){
            	this.reloadTable(tables[index]);
            }
        },
        loadScript : function(url, callback){
        	var callback = callback || function(){};
        	_loadScript(url, callback);
        }
    };
}();

App.init();