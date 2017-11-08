var hTipoDocumento = new function(){


	this.disable = function( id ){
		AppAlert.success({
			title : 'Desactivando'
		})
	}


	this.delete = function( id ){
		AppAlert.confirm({


			preConfirm : function(){
				return new Promise(function (resolve) {
                    setTimeout(function () {
                        resolve();
                    }, 50);
                });
			},
			then : function(result){
				AppAlert.success({
					timer : 1000
				})
			}

		});

	}


}