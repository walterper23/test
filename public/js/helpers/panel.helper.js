'use strict';

var hPanel = function(){

    var url_manager = '/panel/documentos/manager';

    var _verAnexos = function(id){
        App.openModal({
            id    : 'anexos-escaneos',
            url   : '/documento/local/anexos',
            data  : { id },
            btnOk : false,
            btnCancelText : 'Cerrar'
        });
    };

    var _verEscaneos = function(id){
        App.openModal({
            id    : 'anexos-escaneos',
            url   : '/documento/local/escaneos',
            data  : { id },
            btnOk : false,
            btnCancelText : 'Cerrar'
        });
    };

    var _cambiarEstado = function(id){
        App.openModal({
            id : 'form-cambio-estado-documento',
            size : 'modal-lg',
            url  : '/panel/documentos/cambio-estado',
            data : { documento : id }
        });
    };

    var _editarCambioEstado = function(id){
        App.openModal({
            id : 'form-editar-cambio-estado-documento',
            size : 'modal-lg',
            url  : '/panel/documentos/editar-cambio-estado',
            data : { seguimiento : id }
        });
    };

    var _marcarImportante = function(element, id){
        App.ajaxRequest({
            url  : url_manager,
            data : { action : 3, documento : id },
            success : function(result){
                if ( result.status ){
                    if ( result.importante ){
                        $(element).closest('.section-options').find('i.star').removeClass('fa-star-o').addClass('fa-star').addClass('text-warning');
                        AppAlert.notify({
                            type : 'warning',
                            message : result.message
                        });
                    }else{
                        $(element).closest('.section-options').find('i.star').removeClass('fa-star').removeClass('text-warning').addClass('fa-star-o');
                    }
                }else{

                }
            }
        });
    };

    var _marcarArchivado = function( element, id ){
        AppAlert.confirm({
            title : 'Archivar documento',
            text : '¿Desea archivar/desarchivar el documento?',
            then : function(){
                App.ajaxRequest({
                    url  : url_manager,
                    data : { action : 4, documento : id },
                    success : function(result){
                        if ( result.status ){
                            if ( result.archivado ){
                                $(element).closest('.section-options').find('i.archive').addClass('text-primary');
                                $('#arch').text('Desarchivar');
                                AppAlert.notify({
                                    type : result.type,
                                    message : result.message
                                });
                            }else{
                                $(element).closest('.section-options').find('i.archive').removeClass('text-primary');
                                $('#arch').text('Archivar');
                            }
                        }else{

                        }
                    }
                });
            }
        });
    };

    var _nuevoEstado = function(){
        App.openModal({
            id : 'form-estado-documento',
            url : '/configuracion/catalogos/estados-documentos/nuevo',
        });
    };

    var _expediente = function( id ){
        App.openModal({
            id : 'form-no-expediente-denuncia',
            url : '/panel/documentos/no-expediente-denuncia',
            data : { id }
        });
    };

    return {
        verAnexos : function( id ){
            _verAnexos(id);
        },
        verEscaneos : function( id ){
            _verEscaneos(id);
        },
        cambiarEstado : function( id ){
            _cambiarEstado(id);
        },
        marcarImportante : function( element, id ){
            _marcarImportante(element, id);
        },
        marcarArchivado : function( element, id ){
            _marcarArchivado( element, id );
        },
        nuevoEstado : function(){
            _nuevoEstado();
        },
        expediente : function(id){
            _expediente(id);
        }
    };
}();


var appPanel = new Vue({
    el : '#panel-documentos',
    mounted : function(){

    },
    methods : {
        recargarPagina : function(){
            location.reload();
        },
        paginar : function (url){
            if( url.length )
            {
                location.href = url;
            }
        }
    }
});