@extends('vendor.templateModal')

@section('title')<i class="fa fa-fw fa-user-plus"></i> {!! $title !!}@endsection

@section('content')
    <div class="js-wizard-simple block">
        <!-- Step Tabs -->
        <ul class="nav nav-tabs nav-tabs-block nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active show" href="#wizard-progress-step1" data-toggle="tab">Inicio de sesión</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#wizard-progress-step2" data-toggle="tab">Información personal</a>
            </li>
        </ul>
        <!-- END Step Tabs -->

        <!-- Form -->
        {{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
    	{{ Form::hidden('action',1) }}
            <!-- Wizard Progress Bar -->
            <div class="block-content block-content-sm">
                <div class="progress" data-wizard="progress" style="height: 8px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 34.3333%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <!-- END Wizard Progress Bar -->

            <!-- Steps Content -->
            <div class="block-content block-content-full tab-content" style="min-height: 265px;">
                <!-- Step 1 -->
                <div class="tab-pane active show" id="wizard-progress-step1" role="tabpanel">
                    {!! Field::email('usuario','',['label'=>'Usuario','addClass'=>'text-lowercase','autofocus','required']) !!}
    			    {!! Field::password('password','',['label'=>'Contraseña','required']) !!}
    			    {!! Field::password('password_confirmation','',['label'=>'Confirmar contraseña','required']) !!}
                </div>
                <!-- END Step 1 -->

                <!-- Step 2 -->
                <div class="tab-pane" id="wizard-progress-step2" role="tabpanel">
                    {!! Field::text('descripcion','',['label'=>'Descripción','placeholder'=>'Ej. Director de Operaciones','required']) !!}
    			    {!! Field::select('genero','',['label'=>'Género','required'],['HOMBRE'=>'HOMBRE','MUJER'=>'MUJER']) !!}
    			    {!! Field::text('nombres','',['label'=>'Nombre(s)','required']) !!}
    			    {!! Field::text('apellidos','',['label'=>'Apellido(s)','required']) !!}
    			    {!! Field::email('email','',['label'=>'E-mail','required']) !!}
    			    {!! Field::text('teléfono','',['label'=>'Teléfono','placeholder'=>'Opcional']) !!}
                </div>
                <!-- END Step 2 -->

                <!-- Step 3 -->
                <div class="tab-pane" id="wizard-progress-step3" role="tabpanel">
                    
                </div>
                <!-- END Step 3 -->
            </div>
            <!-- END Steps Content -->

            <!-- Steps Navigation -->
            <div class="block-content block-content-sm block-content-full bg-body-light">
                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-alt-secondary disabled" data-wizard="prev">
                            <i class="fa fa-angle-left mr-5"></i> Anterior
                        </button>
                    </div>
                    <div class="col-6 text-right">
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
@endsection

@push('js-script')
{{ Html::script('js/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}
{{ Html::script('js/pages/be_forms_wizard.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">
	'use strict';
	$.extend(new AppForm, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{$form_id}}';

        this.start = function(){
            console.log(this.context)
        }
	
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
                    required : 'Introduzca un nombre usuario',
                    email : 'Introduzca un correo electrónico válido',
                    maxlength : 'Máximo {0} caracteres'
                },
                password : {
                    required : 'Introduzca una contraseña',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                password_confirmation : {
                    required : 'Confirme la contraseña',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres',
                    equalTo : 'Las contraseñas no coinciden',
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
                    required : 'Introduzca el correo electrónico del usuario'
                }
			}
		}
	}).init().start();

</script>
@endpush