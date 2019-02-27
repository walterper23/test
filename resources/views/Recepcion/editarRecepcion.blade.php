@extends('app.layoutMaster')

@section('title', title('Editar recepción de documento') )

@include('vendor.plugins.datepicker')
@include('vendor.plugins.select2')

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-home"></i> Recepci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('recepcion/documentos') }}">Documentos</a>
        <span class="breadcrumb-item active">{{ $panel_titulo }}</span>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
           <!-- Normal Form -->
            <div class="block block-themed" id="{{ $context }}">
                <div class="block-header bg-flat">
                    <h3 class="block-title"><i class="fa fa-fw fa-edit"></i> {{ $panel_titulo }}</h3>
                    <div class="block-options">
                        <div class="dropdown">
                            <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Opciones</button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @can('SIS.ADMIN.ANEXOS')
                                <a class="dropdown-item" title="Crear nuevo anexo para añadir a la lista" href="javascript:void(0)" onclick="hRecepcion.nuevoAnexo('form-anexo','{{ url('configuracion/catalogos/anexos/nuevo') }}')">
                                    <i class="fa fa-fw fa-plus"></i> Nuevo anexo
                                </a>
                                @endcan
                                <!--a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-save mr-5"></i>Guardar captura
                                </a>
                                <div class="dropdown-divider"></div-->
                                <a class="dropdown-item" onclick="hRecepcion.cancelar(2)">
                                    <i class="fa fa-fw fa-times text-danger"></i>Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content">
                    {!! $form !!}
                </div>
                <div class="block-content block-content-full block-content-sm bg-body-light text-right">
                    <button class="btn btn-default" id="btn-cancel" onclick="hRecepcion.cancelar(2)"><i class="fa fa-fw fa-times text-danger"></i> Cancelar</button>
                    <!--button class="btn btn-alt-primary" tabindex="-1"><i class="fa fa-save"></i> Guardar captura</button-->
                    <button class="btn btn-primary" id="btn-ok"><i class="fa fa-fw fa-edit"></i> Guardar cambios</button>
                </div>
            </div>
            <!-- END Normal Form -->
        </div>
    </div>
    <template id="template-progress-bar">
        <span id="span-message">Confirme la información antes de continuar</span>
        <div id="content-progress-bar" class="progress push mb-5 mt-5" style="display:none">
            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <span id="progress-bar-label" class="progress-bar-label">0%</span>
            </div>
        </div>
    </template>
@endsection

@push('js-script')
    {{ Html::script('js/helpers/recepcion.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">
    'use strict';
    
    var formRecepcion = new AppForm;

    var inputEscaneos = [];
    
    $.extend(formRecepcion, new function(){

        this.context_   = '#{{ $context }}';
        this.form_      = '#{{ $form_id }}';
        this.btnOk_     = '#btn-ok';
        this.btnCancel_ = '#btn-cancel';

        this.start = function(){

            var self = this;

            Codebase.helpers(['datepicker','select2']);

            this.form.validate({
                ignore: 'input[type=hidden]'
            });

            var labelNumero    = $('label[for=numero]');
            var iconNumero     = labelNumero.find('i').get(0).outerHTML;
            var selectDenuncia = $('#denuncia');
            var txtAnexo       = this.form.find('#anexos');
            var escaneoNuevo   = $('#escaneo_nuevo');
            var escaneoGroup   = $('#escaneo_group');
            var escaneoInput   = escaneoGroup.html();

            selectDenuncia.closest('div.form-group.row').hide();

            var selectTipoDocumento = this.form.find('#tipo_documento').on('change',function(e){
                
                let label = $(this).find('option:selected').data('label');

                selectDenuncia.closest('div.form-group.row').hide();

                if( label && label.length ){
                    labelNumero.text(label).append(iconNumero)
                    labelNumero.closest('div.form-group.row').show();
                }else{
                    labelNumero.closest('div.form-group.row').hide();
                }

                if( this.value == 2 ){
                    selectDenuncia.closest('div.form-group.row').show();
                }

            });

            var selectAnexo = this.form.find('#anexo').on('change',function(e){
                if( this.value.length ){
                    var anexo = $(this).find('option:selected').text();
                    anexo = ((txtAnexo.val()).trim() + '\n' + anexo).trim();
                    txtAnexo.val( anexo );
                }
            });
            
            $('#addAnexo').on('click',function(){
                selectAnexo.trigger('change');
            });

            escaneoNuevo.on('click',function(){
                if (! escaneoGroup.find('input[type=text]:disabled').length){
                    escaneoGroup.append( escaneoInput );
                }
            });

            escaneoGroup.on('click','.escaneo_buscar',function(){
                $(this).closest('.form-group').find('input[type=file]').on('change',function(e){
                    var inputText = $(this).closest('.form-group').find('input[type=text]');
                    if ( this.value.length ){
                        if( this.files[0].size > 3000000 ){
                            AppAlert.notify({
                                icon : 'fa fa-warning',
                                type : 'danger',
                                message : 'El archivo es mayor a 3 Mb',
                                z_index : 9999
                            });
                            return false;
                        }else{
                            inputText.val(this.files[0].name)
                            inputText.prop('disabled',false).focus();
                            self.updateConteoEscaneos()
                        }
                    }else{
                        inputText.prop('disabled',true)
                    }
                }).click();
            });

            escaneoGroup.on('click','.escaneo_eliminar',function(){
                $(this).closest('.form-group').remove();
                self.updateConteoEscaneos()
            });

            selectTipoDocumento.change();
        }

        this.updateConteoEscaneos = function(){
            let conteo = {{ $escaneos->count() }};

            let inputs = $('#modal-escaneos').find('input[type="file"][name="escaneo"]');

            $.each(inputs,function(key,item){
                if( item.files.length ) conteo++;
            });

            $('#conteo-escaneos').text(conteo);
        }

        this.submitHandler = function(form){
            var form = $(form);
            
            if(!form.valid()){
                return false;
            }

            AppAlert.waiting({
                type  : 'info',
                title : 'Guardar cambios',
                text  : 'Confirme la información antes de continuar',
                html  : $('#template-progress-bar').html(),
                okBtnText : 'Continuar',
                cancelBtnText : 'Regresar',
                showLoader : true,
                preConfirm : function(){
                    $('#content-progress-bar').show();

                    var requestFormPromise = new Promise(function(resolve, reject) {
                        
                        $('#span-message').text('Espere un momento...');
                        
                        App.ajaxRequest({
                            url   : form.attr('action'),
                            type  : form.attr('method'),
                            data  : form.serialize(),
                            success : function(result){
                                inputEscaneos = $('input[type="file"][name="escaneo"]');

                                if( inputEscaneos.length > 0 )
                                {
                                    formRecepcion.subirEscaneo(resolve, reject, form, result.documento, 0, result);
                                }
                                else
                                {
                                    resolve(result);
                                }
                            },
                            error : function(result){
                                resolve(result)
                            }
                        });

                    });

                    return requestFormPromise;
                },
                then : function(result){
                    if( result.status ){
                        AppAlert.success({
                            title : 'Cambios guardados',
                            text  : 'Los cambios se han guardado correctamente',
                            then  : function(){
                                location.href = '{{ url()->previous() }}';
                            }
                        });
                    }else{
                        AppAlert.error({
                            title : 'Ha ocurrido un error',
                            text  : result.message
                        });
                    }
                }
            });
        };

        this.subirEscaneo = function(resolve, reject, form, documento, indexFile, result){

            if( typeof inputEscaneos[indexFile] !== 'undefined' && typeof inputEscaneos[indexFile].files[0] !== 'undefined' )
            {
                $('#progress-bar').css('width','0%');

                var nombreEscaneo = $(inputEscaneos[indexFile]).closest('.form-group').find('input[type=text][name="escaneo_nombre"]').val();

                var inputFile = inputEscaneos[indexFile].files[0];

                var form_data = new FormData();
                form_data.append('documento',documento);
                form_data.append('escaneo', inputFile)

                if( nombreEscaneo.length )
                {
                    form_data.append('nombre_escaneo',nombreEscaneo);
                }
                else
                {
                    nombreEscaneo = indexFile + 1;
                }

                $.ajax({
                    url : '{{ $url_send_form_escaneo }}',
                    type: form.attr('method'),
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
                            setTimeout(formRecepcion.subirEscaneo(resolve, reject, form, documento, indexFile + 1, result),2000)
                        },false)

                        xhr.addEventListener('error',function(event){
                            alert(event);
                        },false)

                        return xhr;
                    }
                });

            }else{
                resolve(result);
            }
        }

        this.rules = function(){
            return {
                tipo_documento : { required : true },
                numero  : {
                    required : {
                        depends : function(){
                            return $('#tipo_documento').val() != 1;
                        }
                    }
                },
                recepcion : { required : true, date : true },
                municipio : { required : true },
                denuncia : {
                    required : true
                },
                descripcion : { required : true },
                responsable : { required : true },

            }
        }

        this.messages = function(){
            return {
                tipo_documento : { required : 'Seleccione el tipo de documento' },
                numero : { required : 'Introduzca el número del documento' },
                recepcion : {
                    required : 'Introduzca la fecha de recepción',
                    date : 'La fecha de recepción no es válida'
                },
                municipio : { required : 'Seleccione un municipio' },
                denuncia : { required : 'Seleccione el expediente donde se agregará el presente documento' },
                descripcion : { required : 'Introduzca el asunto o descripción' },
                responsable : { required : 'Introduzca el nombre del responsable' },
            }
        }
    }).init().start();
</script>
@endpush