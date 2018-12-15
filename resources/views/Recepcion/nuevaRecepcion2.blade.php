@extends('app.layoutMaster')

@section('title', title('Nueva recepción de documento') )

@include('vendor.plugins.datepicker')
@include('vendor.plugins.select2')


@section('content')
    <form id="my_form" enctype="multipart/form-data" method="post" action="{{ url('recepcion/documentos/nuevo-escaneo') }}">
      <input type="file" class="escaneo" /><br>
      <input type="file" class="escaneo" /><br>
      <progress id="progressBar" value="0" max="100" style="width:300px;"></progress>
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

    $("#my_form").submit(function(event){
        event.preventDefault(); //prevent default action

        AppAlert.waiting({
            type  : 'info',
            title : 'Recepcionar documento',
            html  :
            `<span id="resultado">Confirme la información antes de continuar</span>
            <progress id="progressBar2" value="0" max="100" style="width:300px;"></progress>`,
            okBtnText : 'Continuar',
            cancelBtnText : 'Regresar',
            showLoader : true,
            preConfirm : function(){
                var requestForm = new Promise(function(resolve, reject) {

                    $('#resultado').text('Cambiando texto');
                    $('#progressBar2').val(67);
                });

                return requestForm;
                // return new Promise(function(resolve, reject) {
                //     App.ajaxRequest({
                //         url   : form.attr('action'),
                //         data  : new FormData(form[0]),
                //         cache : false,
                //         processData : false,
                //         contentType : false,
                //         success : function(result){
                //             resolve(result)
                //         },
                //         error : function(result){
                //             resolve(result)
                //         }
                //     });

                // });
            },
            then : function(result){
                alert('then()');
                // if( result.status ){
                //     AppAlert.success({
                //         title : 'Recepción exitosa',
                //         text  : 'El documento ha sido recepcionado correctamente',
                //         then  : function(){
                //             location.href = result.message;
                //         }
                //     });
                // }else{
                //     AppAlert.error({
                //         title : 'Recepción fallida',
                //         text  : result.message
                //     });
                // }
            }
        });
        
        // inputEscaneos = $('input.escaneo');
        // var nombreEscaneo = 'mi archivo';
        
        // var tipo = 'local';
        // var documento = 1;

        // if( inputEscaneos.length > 0 )
        // {
        //     enviarDocumento($(this), tipo, documento, 0, nombreEscaneo);
        // }
            
    });


    function enviarDocumento(form, tipo, documento, indexFile, nombreEscaneo)
    {
        if( typeof inputEscaneos[indexFile] !== 'undefined' )
        {
            var inputFile = inputEscaneos[indexFile].files[0];

            nombreEscaneo = nombreEscaneo + ' ' + indexFile;

            var post_url = form.attr("action"); //get form action url
            var request_method = form.attr("method"); //get form GET/POST method

            var form_data = new FormData(); //Encode form elements for submission
            form_data.append('tipo','local');
            form_data.append('documento',documento);
            form_data.append('escaneo', inputFile)
            form_data.append('nombre_escaneo',nombreEscaneo);

            $.ajax({
                url : post_url,
                type: request_method,
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
                            console.log(percent)
                            //update progressbar
                            // $("#upload-progress .progress-bar").css("width", + percent +"%");
                            $("#progressBar").val(percent);
                            $("#loaded_n_total").text( "Uploaded " + position + " bytes of " + total );
                            $("#status").text( Math.round(percent) + "% uploaded... please wait" );
                            $("#status_nombre").text('Subiendo ' + nombreEscaneo );
                        }, false);
                    }

                    xhr.addEventListener('load',function(event){
                        alert(event.target.responseText);
                        $("#progressBar").val(0); //wil clear progress bar after successful upload
                        setTimeout(enviarDocumento(form, tipo, documento, indexFile + 1, nombreEscaneo),1500)
                    },false)

                    return xhr;
                }
            }).done(function(response){ //
                // response = JSON.parse(response)
                // alert(response);
                $("#server-results").html(response);
            });
        }
    }
    
    function _(el) {
      return document.getElementById(el);
    }

    function uploadFile() {
      var file = _("file1").files[0];
      // alert(file.name+" | "+file.size+" | "+file.type);
      var formdata = new FormData();
      formdata.append("file1", file);
      var ajax = new XMLHttpRequest();
      ajax.upload.addEventListener("progress", progressHandler, false);
      ajax.addEventListener("load", completeHandler, false);
      ajax.addEventListener("error", errorHandler, false);
      ajax.addEventListener("abort", abortHandler, false);
      ajax.open("POST", "file_upload_parser.php"); // http://www.developphp.com/video/JavaScript/File-Upload-Progress-Bar-Meter-Tutorial-Ajax-PHP
      //use file_upload_parser.php from above url
      ajax.send(formdata);
    }

    function progressHandler(event) {
      console.log(event)
      _("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
      var percent = (event.loaded / event.total) * 100;
      _("progressBar").value = Math.round(percent);
      _("status").innerHTML = Math.round(percent) + "% uploaded... please wait";
    }

    function completeHandler(event) {
      _("status").innerHTML = event.target.responseText;
      _("progressBar").value = 0; //wil clear progress bar after successful upload
    }

    function errorHandler(event) {
      _("status").innerHTML = "Upload Failed";
    }

    function abortHandler(event) {
      _("status").innerHTML = "Upload Aborted";
    }

</script>
    
@endpush