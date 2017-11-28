@extends('Tema.app')

@section('title')
	SIGESD :: Nueva recepción
@endsection

@push('css-style')
    {{ Html::style('js/plugins/sweetalert2/sweetalert2.min.css') }}
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
                    <h3 class="block-title">Nueva recepci&oacute;n</h3>
                    <div class="block-options">
                        <div class="dropdown">
                            <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Opciones</button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-check mr-5"></i>Validar
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-save mr-5"></i>Guardar
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
                        <button class="btn btn-success"><i class="fa fa-check"></i> Validar</button>
                        <button class="btn btn-info"><i class="fa fa-save"></i> Guardar</button>
                        <button class="btn btn-primary ml-20 pull-right" id="btn-recepcionar">Recepcionar</button>
                        <button class="btn btn-default pull-right" onclick="hRecepcion.cancelar()">Cancelar</button>
                    </div>
                </div>
            </div>
            <!-- END Normal Form -->
        </div>
    </div>
@endsection

@push('js-script')
    {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
    {{ Html::script('js/helpers/recepcion.helper.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">

    $.extend(AppForm, new function(){

        this.init = function(){
            var self = this;
            this.context = $('#{{ $context }}')
            this.form = $('#{{ $form_id }}')
            this.btnOk = this.context.find('#btn-recepcionar')
            this.btnCancel = this.context.find('#btn-cancel')
            this.btnClose = this.context.find('[data="close-modal"]')

            this.formSubmit(this.form)

            this.cloneForm = this.form.clone()

            this.btnOk.on('click', function(e){
                self.submit()
            });

            this.btnClose.on('click', function(e){
                self.onClose()
            })
        }
    
        this.submitHandler = function(form){
            console.log('viene')
            if(!$(form).valid()){
                return false;
            }

            AppAlert.waiting({
                type  : 'info',
                title : 'Recepcionar documento',
                text  : 'Confirme la información antes de continuar',
                okBtnText : 'Continuar',
                cancelBtnText : 'Regresar',

            })
            /*
            App.ajaxRequest({
                url  : $(form).attr('action'),
                data : $(form).serialize(),
                before : function(){
                    
                },
                success : function(data){
                    if( data.status ){
                        AppAlert.notify({
                            type : 'info',
                            message : data.message
                        })
                    }else{

                        if( data.errors != undefined){
                            $.each(data.errors,function(index, value){
                                error = $('<div/>').addClass('invalid-feedback').attr('id',index+'-error').text(value[0]);
                                $('#'+index).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
                                $('#'+index).parents('.form-group > div').append(error);
                            })
                        }


                        
                    }
                }
            })
            */
        }

        this.rules = function(){
            return {
                tipo_documento : { required : true },
                anio : { required : true }
            }
        }

        this.messages = function(){
            return {
                nombre : { required : 'Seleccione el tipo de documento' },
                anio   : { required : 'Introduzca el año' }
            }
        }
    }).init()
</script>
@endpush