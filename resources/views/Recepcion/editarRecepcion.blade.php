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
@endsection

@push('js-script')
    {{ Html::script('js/helpers/recepcion.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">
    'use strict';
    var formRecepcion = new AppForm;
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

            var labelNumero = $('label[for=numero]');
            var selectDenuncia = $('#denuncia');
            var txtAnexo = this.form.find('#anexos');
            var escaneoNuevo = $('#escaneo_nuevo');
            var escaneoGroup = $('#escaneo_group');
            var escaneoInput = escaneoGroup.html();

            selectDenuncia.closest('div.form-group.row').hide();

            var selectTipoDocumento = this.form.find('#tipo_documento').on('change',function(e){
                
                let label = $(this).find('option:selected').data('label');

                selectDenuncia.closest('div.form-group.row').hide();

                if( label && label.length ){
                    labelNumero.text( label );
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
                if (! escaneoGroup.find('input[type=text]:disabled').length)
                    escaneoGroup.append( escaneoInput );
            });

            escaneoGroup.on('click','.escaneo_buscar',function(){
                $(this).closest('.form-group').find('input[type=file]').change(function(){
                    var inputText = $(this).closest('.form-group').find('input[type=text]');
                    if ( this.value.length ){
                        inputText.prop('disabled',false).focus();
                        self.updateConteoEscaneos()
                    }else{
                        inputText.prop('disabled',true)
                    }
                }).click();
            });

            escaneoGroup.on('click','.escaneo_eliminar',function(){
                $(this).closest('.form-group').remove();
                self.updateConteoEscaneos()
            });

        }

        this.updateConteoEscaneos = function(){
            let conteo = '';

            let inputs = $('#modal-escaneos').find('input[type="file"][name="escaneo[]"]');

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
                okBtnText : 'Continuar',
                cancelBtnText : 'Regresar',
                showLoader : true,
                preConfirm : function(){
                    return new Promise(function(resolve, reject) {
                        App.ajaxRequest({
                            url   : form.attr('action'),
                            data  : new FormData(form[0]),
                            cache : false,
                            processData : false,
                            contentType : false,
                            success : function(result){
                                resolve(result)
                            },
                            error : function(result){
                                resolve(result)
                            }
                        });

                    });
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