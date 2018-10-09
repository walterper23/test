'use strict';

var AppNotificacion = new Vue({
    el : '#header-notifications',
    data : {
        notificaciones : [],
    },
    mounted : function(){
        $('#icon-bell-notification').addClass('d-none');

        this._getNotificaciones();
    },
    methods : {
        _getNotificaciones : function(){
            axios.post('/manager',{
                action : 'get-notificaciones'
            })
            .then( response => {
                this._drawNotificaciones(response.data);
            })
        },
        _drawNotificaciones : function( notificaciones ){
            this.notificaciones = notificaciones;
            this._setNumberIcon( notificaciones.length );
        },
        _setNumberIcon : function( number ){
            if( number > 0 ){

                $('#icon-bell-notification').text(number).removeClass('d-none');

                this._setTitlePage( number );    
            }
        },
        _setTitlePage : function( number ){
            if ( number > 0 ){
                var title = '(' + number + ') ' + document.title;
                document.title = title;
            }
        }
        
    }
});