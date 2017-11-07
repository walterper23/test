var App = new function(){

	this.init = function(){
		this.configAjaxSetup()
	}

	this.configAjaxSetup = function(){
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	}

	this.ajaxRequest = function( options ){
		$.ajax({
			url  : options.url,
			data : options.data || {},
			type : options.type ||'POST',
			beforeSend : options.before || function(){},
			success : options.success || function(){},
			complete : options.complete || function(){},
			error : options.error || function(){}
		});
	}

	this.openModal = function( config ){

		id = config.id || 'modal-app'
		var modal = $('div.modal.fade#modal-' + id )
		modal = modal.length > 0 ? modal : $('<div/>').addClass('modal fade').attr('id','modal-'+id).attr('role','dialog')

		title = config.title || ''
		header = config.header != undefined ? config.header : true
		footer = config.footer != undefined ? config.footer : true
		btnCancel = config.btnCancel != undefined ? config.btnCancel : true
		btnCancelText = config.btnCancelText || 'Cancelar'
		btnOk = config.btnOk != undefined ? config.btnOk : true
		btnOkText = config.btnOkText || 'Aceptar'
		
		html = `<div class="modal-dialog">
	                <div class="modal-content">
	                	<div class="block block-themed block-transparent mb-0 block-mode-loading" style="min-height:120px;"></div>`

	    if( footer ){
	        html +=    `<div class="modal-footer">`
	        if( btnCancel ) html += `<button type="button" class="btn btn-alt-default" data-dismiss="modal" id="btn-cancel">`+btnCancelText+`</button>`
	        if( btnOk )     html += `<button type="button" class="btn btn-alt-primary" id="btn-ok">`+btnOkText+`</button>`
	        html +=    `</div>`
	    }
	    
	    html +=     `</div>
	            </div>`
	    
		modal.html( html )

		$('body').append(modal)

		this.ajaxRequest({
			url  : config.url,
			data : config.data || {},
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
}

App.init()