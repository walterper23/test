'use strict';

var AppNotification = function(){

    var _init = function(){

        $('#icon-bell-notification').addClass('d-none');

        _getNotifications();

    }

    var _getNotifications = function(){
        
        _drawNotifications( [ 5, 6, 7 ] );
        
        /*
        App.ajaxRequest({

            success : function( result ){
                _drawNotifications( { a, c, d } );
            }
        });
        */
    }

    var _drawNotifications = function( notifications ){
        _setNumberIcon( parseInt(Math.random(0,1)*10) );
    }

    var _setNumberIcon = function( number ){
        if( number > 0 ){

            $('#icon-bell-notification').text(number).removeClass('d-none');

            _setTitlePage( number );    
        }
    }

    var _setTitlePage = function( number ){
        if ( number > 0 ){
            var title = '(' + number + ') ' + document.title;
            document.title = title;
        }
    }

    return {
        init : function(){
            _init();
        },
    };

}();

AppNotification.init();