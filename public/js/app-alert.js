var AppAlert = new function(){
	

	this.confirm = function( options ){
		swal({
            title: 'Are you sure?',
            text: 'You will not be able to recover this imaginary file!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d26a5c',
            confirmButtonText: 'Yes, delete it!',
            html: false,
            preConfirm: function() {
                return new Promise(function (resolve) {
                    setTimeout(function () {
                        resolve();
                    }, 50);
                });
            }
        }).then(
            function (result) {
                swal('Deleted!', 'Your imaginary file has been deleted.', 'success');
            }, function(dismiss) {
                // dismiss can be 'cancel', 'overlay', 'esc' or 'timer'
            }
        );
	}

}