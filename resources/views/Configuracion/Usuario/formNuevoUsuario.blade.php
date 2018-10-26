@extends('vendor.modal.template',['headerColor'=>'bg-earth'])

@section('title')<i class="fa fa-fw fa-user-plus"></i> {!! $title !!}@endsection

@section('content')
    <!-- Validation Wizard Classic -->
    <div id="js-wizard-validation-form" style="margin: -20px">
        <!-- Step Tabs -->
        <ul class="nav nav-tabs nav-tabs-block nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#wizard-validation-classic-step1" data-toggle="tab">Inicio de sesión</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#wizard-validation-classic-step2" data-toggle="tab">Datos personales</a>
            </li>
        </ul>
        <!-- END Step Tabs -->

        <!-- Wizard Progress Bar -->
        <div class="block-content block-content-sm">
            <div class="progress" data-wizard="progress" style="height: 8px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <!-- END Wizard Progress Bar -->

        <!-- Form -->
        {{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
        {{ Form::hidden('action',$action) }}
            <!-- Steps Content -->
            <div class="block-content block-content-full tab-content" style="min-height: 265px;">
                <!-- Step 1 -->
                <div class="tab-pane active" id="wizard-validation-classic-step1" role="tabpanel">
                    {!! Field::email('usuario','',['label'=>'Usuario','addClass'=>'text-lowercase','autofocus','required']) !!}
                    {!! Field::password('password','',['label'=>'Contraseña','required']) !!}
                    {!! Field::password('password_confirmation','',['label'=>'Confirmar contraseña','required']) !!}
                </div>
                <!-- END Step 1 -->

                <!-- Step 2 -->
                <div class="tab-pane" id="wizard-validation-classic-step2" role="tabpanel">
                    {!! Field::text('notrabajador','',['label'=>'Nó. trabajador','placeholder'=>'Opcional','maxlength'=>10]) !!}
                    {!! Field::text('descripcion','',['label'=>'Descripción','placeholder'=>'Ej. Director de Operaciones','required','maxlength'=>255]) !!}
                    {!! Field::text('nombres','',['label'=>'Nombre(s)','required','maxlength'=>255]) !!}
                    {!! Field::text('apellidos','',['label'=>'Apellido(s)','required','maxlength'=>255]) !!}
                    {!! Field::select('genero','',['label'=>'Género','required'],['HOMBRE'=>'HOMBRE','MUJER'=>'MUJER']) !!}
                    {!! Field::email('email','',['label'=>'E-mail','required','maxlength'=>255]) !!}
                    {!! Field::text('teléfono','',['label'=>'Teléfono','placeholder'=>'Opcional','maxlength'=>25]) !!}
                </div>
                <!-- END Step 2 -->
            </div>
            <!-- END Steps Content -->

            <!-- Steps Navigation -->
            <div class="block-content block-content-sm block-content-full bg-body-light">
                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn btn-alt-secondary" data-close="modal"><i class="fa fa-fw fa-times text-danger"></i> Cancelar</button>
                        <button type="submit" class="btn btn-alt-primary d-none pull-right" data-wizard="finish">
                            <i class="fa fa-check mr-5"></i> Finalizar
                        </button>
                        <button type="button" class="btn btn-alt-secondary pull-right" data-wizard="next">
                            Siguiente <i class="fa fa-angle-right ml-5"></i>
                        </button>
                        <button type="button" class="btn btn-alt-secondary disabled pull-right mr-5" data-wizard="prev">
                            <i class="fa fa-angle-left mr-5"></i> Anterior
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
@endpush

@push('js-custom')
<script type="text/javascript">
	'use strict';
    var formUser = new AppForm;
	$.extend(formUser, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';

        this.start = function(){

            var self = this;

            // Prevent forms from submitting on enter key press
            self.form.on('keyup keypress', function (e) {
                var code = e.keyCode || e.which;

                if (code === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            // Init form validation on classic wizard form
            var validator = self.form.validate();

            // Init classic wizard with validation
            jQuery('#js-wizard-validation-form').bootstrapWizard({
                tabClass: 'nav nav-tabs nav-tabs-block nav-fill',
                nextSelector : '[data-wizard="next"]',
                previousSelector : '[data-wizard="prev"]',
                finishSelector : '[data-wizard="finish"]',
                onTabShow: function(tab, navigation, index) {
                    var percent = ((index + 1) / navigation.find('li').length) * 100;

                    // Get progress bar
                    var progress = navigation.parents('.block').find('[data-wizard="progress"] > .progress-bar');

                    // Update progress bar if there is one
                    if (progress.length) {
                        progress.css({ width: percent + '%' });
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
                notrabajador : { maxlength : 10 },
                descripcion : { required : true, minlength : 3, maxlength : 255 },
                nombres : { required : true, minlength : 1, maxlength : 255 },
                apellidos : { required : true, minlength : 1, maxlength : 255 },
                genero : { required : true },
                email : { required : true, email : true, minlength : 5, maxlength : 255 },
                telefono : { maxlength : 25 }
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
                notrabajador : {
                    maxlength : 'Máximo {0} caracteres'
                },
                descripcion : {
                    required : 'Introduzca la descripción del usuario',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                nombres : {
                    required : 'Introduzca el nombre(s) del usuario',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                apellidos : {
                    required : 'Introduzca los apellidos del usuario',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                genero : {
                    required : 'Seleccione un género'
                },
                email : {
                    required : 'Introduzca el correo electrónico del usuario',
                    email    : 'Introduzca un correo electrónico válido',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                telefono : {
                    maxlength : 'Máximo {0} caracteres'
                }
			}
		};

        this.displayError = function( index, value ){
            AppAlert.notify({
                type    : 'warning',
                icon    : 'fa fa-fw fa-warning',
                message : value[0],
                z_index : 9999
            });
        };

	}).init().start();

</script>
@endpush