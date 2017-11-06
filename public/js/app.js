var App = new function(){

	this.init = function(){
		this.configAjaxSetup()
		block = $('div#btabs-tipos-documentos')
		var elBlock = (block instanceof jQuery) ? block : jQuery(block);
		console.log(Codebase.blocks(elBlock, 'state_loading') )
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
		var modal = $('div.modal.fade#' + id )
		modal = modal.length > 0 ? modal : $('<div/>').addClass('modal fade').attr('id',id).attr('role','dialog')

		title = config.title || ''
		header = config.header != undefined ? config.header : true;
		footer = config.footer != undefined ? config.footer : true;
		
		html = `
			    <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                	<div class="block block-themed block-transparent mb-0 block-mode-loading" style="min-height:120px;"></div>`

	    //              	<div class="block block-content block-mode-loading" margin-bottom:0px"></div>`

	    if( footer ){
	        html +=    `<div class="modal-footer">
	                        <button type="button" class="btn btn-alt-default" data-dismiss="modal">Cerrar</button>
	                        <button type="button" class="btn btn-alt-primary" data-dismiss="modal">
	                            <i class="fa fa-check"></i> Aceptar
	                        </button>
	                    </div>`
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