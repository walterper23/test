@extends('app.layoutMaster')

@section('title')
	{{ title('Nueva recepción de documento') }}
@endsection

@push('css-style')
    {{ Html::style('js/plugins/sweetalert2/sweetalert2.min.css') }}
    {{ Html::style('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}
@endpush

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-home"></i> Recepci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('recepcion/documentos') }}">Documentos</a>
        <span class="breadcrumb-item active">Nueva recepci&oacute;n</span>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
           <!-- Normal Form -->
            <div class="block block-themed">
                <div class="block-header bg-corporate">
                    <h3 class="block-title"><i class="fa fa-fw fa-edit"></i> Nueva recepci&oacute;n</h3>
                    <div class="block-options">
                        <div class="dropdown">
                            <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Opciones</button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-save mr-5"></i>Guardar captura
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" onclick="hRecepcion.cancelar()">
                                    <i class="fa fa-fw fa-times mr-5"></i>Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content" id="{{ $context }}">
                    
                    {!! $form !!}

                    <div class="form-group clearfix">
                        <!--button class="btn btn-success"><i class="fa fa-check"></i> Validar</button-->
                        <button class="btn btn-alt-primary" tabindex="-1"><i class="fa fa-save"></i> Guardar captura</button>
                        <button class="btn btn-primary ml-20 pull-right" id="btn-ok">Recepcionar <i class="fa fa-fw fa-sign-out"></i></button>
                        <button class="btn btn-default pull-right" id="btn-cancel" onclick="hRecepcion.cancelar()">Cancelar</button>
                    </div>
                </div>
            </div>
            <!-- END Normal Form -->
        </div>
    </div>
@endsection

@push('js-script')
    {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
    {{ Html::script('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}
    {{ Html::script('js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}
    {{ Html::script('js/helpers/recepcion.helper.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
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

            Codebase.helper('datepicker');

            var labelNumero = $('label[for=numero]');
            var txtAnexo = this.form.find('#anexos');

            var selectTipoDocumento = this.form.find('#tipo_documento').on('change',function(e){
                labelNumero.text( $(this).find('option:selected').data('label') );
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
            

        }

        this.submitHandler = function(form){
            if(!$(form).valid()){
                return false;
            }

            //var formData = new FormData($(form)[0]);
            
            AppAlert.waiting({
                type  : 'info',
                title : 'Recepcionar documento',
                text  : 'Confirme la información antes de continuar',
                okBtnText : 'Continuar',
                cancelBtnText : 'Regresar',
                then : function(){
                    $(form).unbind('submit').submit();
                }
            })
        }

        this.rules = function(){
            return {
                tipo_documento : { required : true },
                numero  : { required : true },
                recepcion : { required : true, date : true },
                municipio : { required : true },
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
                descripcion : { required : 'Introduzca el asunto o descripción' },
                responsable : { required : 'Introduzca el nombre del responsable' },
            }
        }
    }).init().start();
</script>
@endpush