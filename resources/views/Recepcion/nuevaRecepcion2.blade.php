@extends('app.layoutMaster')

@section('title', title('Nueva recepción de documento') )

@include('vendor.plugins.datepicker')
@include('vendor.plugins.select2')


@section('content')
    <form id="my_form" enctype="multipart/form-data" method="post" action="{{ $url_send_form }}">
      <input type="file" class="escaneo" /><br>
      <input type="file" class="escaneo" /><br>
      <h3 id="status_nombre"></h3>
      <h4 id="status"></h4>
      <p id="loaded_n_total"></p>
      <button type="submit">Subir</button>
    </form>

    <div id="server-results"></div>
@endsection

@push('js-script')
    {{ Html::script('js/helpers/recepcion.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">
    'use strict';

    var inputEscaneos = [];
    var recepcionCreada = false;

    var myForm = $("#my_form");

    myForm.submit(function(event){
        event.preventDefault(); //prevent default action

        AppAlert.waiting({
            type  : 'info',
            title : 'Recepcionar documento',
            html  :
            `<span id="span-message">Confirme la información antes de continuar</span>
            <div id="content-progress-bar" class="progress push mb-5 mt-5" style="display:none">
                <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    <span id="progress-bar-label" class="progress-bar-label">0%</span>
                </div>
            </div>`,
            okBtnText : 'Continuar',
            cancelBtnText : 'Regresar',
            showLoader : true,
            preConfirm : function(){

                $('#content-progress-bar').show();

                var requestForm = new Promise(function(resolve, reject) {

                    $('#span-message').text('Creando recepción de documento...');

                    var result = {};
                    result.tipo = 'local';
                    result.documento = 1;

                    recepcionCreada = true;

                    inputEscaneos = $('input[type="file"].escaneo');
                    var nombreEscaneo = 'mi archivo';


                    if( inputEscaneos.length > 0 )
                    {
                        enviarDocumento(resolve, reject, myForm, result.tipo, result.documento, 0, nombreEscaneo);
                    }
                    else
                    {
                        finalizarRecepcion(resolve, reject, result.documento);
                    }

                });

                return requestForm;
                // return new Promise(function(resolve, reject) {
                //     App.ajaxRequest({
                //         url   : myForm.attr('action'),
                //         data  : myForm.serialize(),
                //         success : function(result){
                //             resolve(result)
                //         },
                //         error : function(result){
                //             recepcionCreada = false;
                //             resolve(result)
                //         }
                //     });

                // });
            },
            then : function(result){
                if( result.status ){
                    AppAlert.success({
                        title : 'Recepción exitosa',
                        text  : 'El documento ha sido recepcionado correctamente',
                        then  : function(){
                            location.href = result.message;
                        }
                    });
                }else{
                    AppAlert.error({
                        title : 'Recepción fallida',
                        text  : result.message
                    });
                }
            }
        });
            
    });


    function enviarDocumento(resolve, reject, form, tipo, documento, indexFile, nombreEscaneo)
    {
        if( typeof inputEscaneos[indexFile] !== 'undefined' && typeof inputEscaneos[indexFile].files[0] !== 'undefined' )
        {
            $('#progress-bar').css('width','0%');

            var inputFile = inputEscaneos[indexFile].files[0];

            nombreEscaneo = nombreEscaneo + ' ' + indexFile;

            var form_data = new FormData();
            form_data.append('tipo',tipo);
            form_data.append('documento',documento);
            form_data.append('escaneo', inputFile)
            form_data.append('nombre_escaneo',nombreEscaneo);

            $.ajax({
                url : '{{ $url_send_form_escaneo }}',
                type: form.attr("method"),
                data : form_data,
                contentType: false,
                processData:false,
                xhr: function(){
                    //upload Progress
                    var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                            var percent = 0;
                            var position = event.loaded || event.position;
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            
                            var progressBar = $('#progress-bar').css('width', percent  + '%');
                            // Update progress label
                            $('#progress-bar-label', progressBar).html(percent  + '%');
                            $('#span-message').html('Subiendo escaneo: <b>' + nombreEscaneo + '</b>');

                        }, false);
                    }

                    xhr.addEventListener('load',function(event){
                        console.log(event.target.responseText);
                        setTimeout(enviarDocumento(resolve, reject, form, tipo, documento, indexFile + 1, nombreEscaneo),1500)
                    },false)

                    xhr.addEventListener('error',function(event){
                        alert(event);
                    },false)

                    return xhr;
                }
            });
        }else{
            finalizarRecepcion(resolve, reject, documento);
        }
    }

    function finalizarRecepcion(resolve, reject, documento){
        
        $('#span-message').html('Finalizando recepción...');
        
        // App.ajaxRequest({
        //     url   : myForm.attr('action'),
        //     data  : { action : 5 , acuse : 1, id : documento },
        //     success : function(result){
        //         resolve(result)
        //     },
        //     error : function(result){
        //         resolve(result)
        //     }
        // });

        resolve({ status:true, message : 'todo bien' });
    }

</script>
    
@endpush