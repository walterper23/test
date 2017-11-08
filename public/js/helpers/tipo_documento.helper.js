'use strict';

var hTipoDocumento = new function(){

	this.edit = function(){

	}

	this.disable = function( id ){
		AppAlert.error({
			title : 'Desactivando'
		})
	}


	this.delete = function( id ){
		AppAlert.waiting({

			enterKey: false,
			then : function(result){
				AppAlert.success({
					
				})
			}
		});

	}


}