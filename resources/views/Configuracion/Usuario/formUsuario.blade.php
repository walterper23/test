@extends('vendor.templateModal',['headerColor'=>'bg-earth'])

@section('title')<i class="fa fa-fw fa-user-plus"></i> {!! $title !!}@endsection

@section('content')
    <!-- Validation Wizard Classic -->
    <div class="block" id="js-wizard-validation-form">
        <!-- Step Tabs -->
        <ul class="nav nav-tabs nav-tabs-block nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#wizard-validation-classic-step1" data-toggle="tab">1. Personal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#wizard-validation-classic-step2" data-toggle="tab">2. Details</a>
            </li>
        </ul>
        <!-- END Step Tabs -->

        <!-- Wizard Progress Bar -->
        <div class="block-content block-content-sm">
            <div class="progress" data-wizard="progress" style="height: 8px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 51%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <!-- END Wizard Progress Bar -->

        <!-- Form -->
        {{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
        {{ Form::hidden('action',1) }}
            <!-- Steps Content -->
            <div class="block-content block-content-full tab-content" style="min-height: 265px;">
                <!-- Step 1 -->
                <div class="tab-pane active" id="wizard-validation-classic-step1" role="tabpanel">
                    {!! Field::email('usuario','crroee@dsas.owss',['label'=>'Usuario','addClass'=>'text-lowercase','autofocus','required']) !!}
                    {!! Field::text('password','123456',['label'=>'Contraseña','required']) !!}
                    {!! Field::text('password_confirmation','123456',['label'=>'Confirmar contraseña','required']) !!}
                </div>
                <!-- END Step 1 -->

                <!-- Step 2 -->
                <div class="tab-pane" id="wizard-validation-classic-step2" role="tabpanel">
                    {!! Field::text('descripcion','',['label'=>'Descripción','placeholder'=>'Ej. Director de Operaciones','required']) !!}
                    {!! Field::select('genero','',['label'=>'Género','required'],['HOMBRE'=>'HOMBRE','MUJER'=>'MUJER']) !!}
                    {!! Field::text('nombres','',['label'=>'Nombre(s)','required']) !!}
                    {!! Field::text('apellidos','',['label'=>'Apellido(s)','required']) !!}
                    {!! Field::email('email','',['label'=>'E-mail','required']) !!}
                    {!! Field::text('teléfono','',['label'=>'Teléfono','placeholder'=>'Opcional']) !!}
                </div>
                <!-- END Step 2 -->
            </div>
            <!-- END Steps Content -->

            <!-- Steps Navigation -->
            <div class="block-content block-content-sm block-content-full bg-body-light">
                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-alt-secondary" data-close="modal"><i class="fa fa-fw fa-times text-danger"></i> Cancelar</button>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn btn-alt-secondary disabled" data-wizard="prev">
                            <i class="fa fa-angle-left mr-5"></i> Anterior
                        </button>
                        <button type="button" class="btn btn-alt-secondary" data-wizard="next">
                            Siguiente <i class="fa fa-angle-right ml-5"></i>
                        </button>
                        <button type="submit" class="btn btn-alt-primary d-none" data-wizard="finish">
                            <i class="fa fa-check mr-5"></i> Finalizar
                        </button>
                    </div>
                </div>
            </div>
            <!-- END Steps Navigation -->
        {{ Form::close() }}
        <!-- END Form -->
    </div>
    <!-- END Validation Wizard Classic -->
@endsection

@push('js-script')
{{ Html::script('js/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}
{{-- Html::script('js/pages/be_forms_wizard.js') --}}

@endpush

@push('js-custom')
<script type="text/javascript">
	'use strict';
    var formUser = new AppForm;
	$.extend(formUser, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';

        this.initFormSubmit = function(){ };

        this.start = function(){

            var self = this;

            $.fn.bootstrapWizard.defaults.tabClass         = 'nav nav-tabs';
            $.fn.bootstrapWizard.defaults.nextSelector     = '[data-wizard="next"]';
            $.fn.bootstrapWizard.defaults.previousSelector = '[data-wizard="prev"]';
            $.fn.bootstrapWizard.defaults.finishSelector   = '[data-wizard="finish"]';

            // Prevent forms from submitting on enter key press
            self.form.on('keyup keypress', function (e) {
                var code = e.keyCode || e.which;

                if (code === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            this.formSubmit(this.form);

            // Init form validation on classic wizard form
            var validator = self.form.validate({
                errorClass: 'invalid-feedback animated fadeInDown',
                errorElement: 'div',
                errorPlacement: function(error, e) {
                    jQuery(e).parents('.form-group').append(error);
                },
                highlight: function(e) {
                    jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
                },
                success: function(e) {
                    jQuery(e).closest('.form-group').removeClass('is-invalid');
                    jQuery(e).remove();
                },
                rules: self.rules(),
                messages: self.messages()
            });

            // Init classic wizard with validation
            jQuery('#js-wizard-validation-form').bootstrapWizard({
                tabClass: '',
                onTabShow: function(tab, navigation, index) {
                    var percent = ((index + 1) / navigation.find('li').length) * 100;

                    // Get progress bar
                    var progress = navigation.parents('.block').find('[data-wizard="progress"] > .progress-bar');

                    // Update progress bar if there is one
                    if (progress.length) {
                        progress.css({ width: percent + 1 + '%' });
                    }
                },
                onNext: function(tab, navigation, index) {
                    if( !self.form.valid() ) {
                        validator.focusInvalid();
                        return false;
                    }
                },
                onTabClick: function(tab, navigation, index) {
                    jQuery('a', navigation).blur();
                    return false;
                }
            });

        };
	
		this.rules = function(){
			return {
                usuario : { required : true, email : true, maxlength : 255 },
				password : { required : true, minlength: 6, maxlength : 20 },
                password_confirmation : { required : true, minlength: 6, maxlength : 20, equalTo : '#password' },
                descripcion : { required : true },
                genero : { required : true },
                nombres : { required : true },
                apellidos : { required : true },
                email : { required : true, email : true }
			}
		}

		this.messages = function(){
			return {
				usuario : {
                    required  : 'Introduzca un nombre usuario',
                    email     : 'Introduzca un correo electrónico válido',
                    maxlength : 'Máximo {0} caracteres'
                },
                password : {
                    required  : 'Introduzca una contraseña',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                password_confirmation : {
                    required  : 'Confirme la contraseña',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres',
                    equalTo   : 'Las contraseñas no coinciden',
                },
                descripcion : {
                    required : 'Introduzca la descripción del usuario'
                },
                genero : {
                    required : 'Seleccione un género'
                },
                nombres : {
                    required : 'Introduzca el nombre(s) del usuario',
                },
                apellidos : {
                    required : 'Introduzca los apellidos del usuario',
                },
                email : {
                    required : 'Introduzca el correo electrónico del usuario',
                    email    : 'Introduzca un correo electrónico válido',
                }
			}
		}
	}).init().start();

</script>
@endpush