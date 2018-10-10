'use strict';

var AppNotificacion = new Vue({
    el : '#header-notifications',
    data : {
        notificaciones : [],
        document_title : document.title,
        icon_bell : '',
    },
    mounted : function(){
        this.icon_bell = $('#icon-bell-notification').addClass('d-none');
        
        this._getNotificaciones();
    },
    watch : {
        notificaciones : function(new_value, old_value){
            this._setNumberIcon( new_value.length );
        }
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
        },
        _setNumberIcon : function( number ){
            this.icon_bell.text(number);

            if( number > 0 ){
                this.icon_bell.removeClass('d-none');
            }else{
                this.icon_bell.addClass('d-none');
            }

            this._setTitlePage( number );    
        },
        _setTitlePage : function( number ){
            if ( number > 0 ){
                document.title = '(' + number + ') ' + this.document_title;
            }else{
                document.title = this.document_title;
            }
        },
        _eliminarNotificacion : function( notificacion ){
            this.notificaciones = this.notificaciones.filter( item => {
                return item != notificacion;
            });
        }
        
    }
});