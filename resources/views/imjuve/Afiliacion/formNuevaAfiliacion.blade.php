@extends('vendor.modal.template',['headerColor'=>'bg-default'])

@section('title')<i class="fa fa-fw fa-user-plus"></i> {!! $title !!}@endsection

@section('content')
<style>
    .maxwidth{
        width: 100%!important;
    }
</style>
{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id,'files'=>true]) }}
    {{ Form::hidden('action',$action) }}
    <div class="row">
        <div class="block col-lg-12">
            <div class="block-header block-header-default">
                <h3 class="block-title">Datos personales</h3>
            </div>
            <div class="block-content row">
                <div class="col-md-8">
                    {!! Field::text('nombres','',['label'=>'Nombre(s)','required','maxlength'=>255]) !!}
                    {!! Field::text('paterno','',['label'=>'A. Paterno','required','maxlength'=>255]) !!}
                    {!! Field::text('materno','',['label'=>'A. Materno','required','maxlength'=>255]) !!}
                    {!! Field::select('genero','',['label'=>'Género','required'],$generos) !!}
                    {!! Field::datepicker('nacimiento',date('Y-m-d'),['label'=>'F. Nacimiento','required','placeholder'=>date('Y-m-d'),'popover'=>['F. Nacimiento','Introduzca la fecha de nacimiento del afiliado']]) !!}
                </div>
                <div class="col-md-7">
                    {!! Field::select('escolaridad','',['label'=>'Escolaridad','class'=>'js-select2 maxwidth',],$escolaridades) !!}
                    {!! Field::select('nacionalidad','',['label'=>'Nacionalidad','class'=>'js-select2 maxwidth'],$nacionalidades) !!}
                </div>
                <div class="col-md-5">
                    {!! Field::select('ecivil','',['label'=>'E. Civil','class'=>'js-select2 maxwidth'],$estados_civiles) !!}
                    {!! Field::select('ocupacion','',['label'=>'Ocupación','class'=>'js-select2 maxwidth'],$ocupaciones) !!}
                </div>
                <div class="col-md-12">
                </div>
            </div>
        </div>
        <div class="block col-lg-12">
            <div class="block-header block-header-default">
                <h3 class="block-title">Dirección y contacto</h3>
            </div>
            <div class="block-content row">
                <div class="col-md-6 form-group row">
                    <label for="cp" class="col-md-5 col-form-label" required="">Código Postal</label>
                    <div class="col-md-7">
                        <div class="input-group">
                            <input required="" maxlength="5" id="cp" class="form-control" name="cp" type="text" value="">
                            <div class="input-group-appen">
                                <button type="button" class="btn btn-secondary">
                                    <i class="si si-refresh"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Field::select('entidad','',['label'=>'Entidad','class'=>'js-select2 maxwidth',],$entidades) !!}
                </div>
                <div class="col-md-6">
                    {!! Field::select('municipio','',['label'=>'Municipio','class'=>'js-select2 maxwidth',],[]) !!}
                </div>
                <div class="col-md-6">
                    {!! Field::select('localidad','',['label'=>'Localidad','class'=>'js-select2 maxwidth',],[]) !!}
                </div>

            
            </div>
        </div>
    </div>
{{ Form::close() }}
@endsection
@push('js-script')
    {{ Html::script('js/helpers/imjuve/afiliado.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush
@push('js-custom')
<script type="text/javascript">
	'use strict';
    var formAfiliacion = new AppForm;
	$.extend(formAfiliacion, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';

        this.start = function(){

            var self = this;
            Codebase.helpers(['datepicker','select2']);

            self.form.on('keyup keypress', function (e) {
                var code = e.keyCode || e.which;

                if (code === 13) {
                    e.preventDefault();
                    return false;
                }
            });
            var entidadSelect   = $("#entidad");
            var municipioSelect = $("#municipio");
            var localidadSelect = $("#localidad");
            var changeEntidad = this.form.find('#entidad').on('change',function(e){
                 App.ajaxRequest({
                    url   : '/imjuve/utils/municipios',
                    type  : 'POST',
                    data  : {'entidad':e.currentTarget.value},
                    success : function(result){
                        $.each(result, function(i, item) {
                            var option = new Option(i,item, true, true);
                            municipioSelect.append(option);
                        });
                    },
                    error : function(result){
                        resolve(result)
                    }
                });
            });
             var changeMunicipio = this.form.find('#municipio').on('change',function(e){
                 App.ajaxRequest({
                    url   : '/imjuve/utils/localidades',
                    type  : 'POST',
                    data  : {'entidad':entidadSelect.val(),'municipio':e.currentTarget.value},
                    success : function(result){
                        $.each(result, function(i, item) {
                            var option = new Option(i,item, true, true);
                            localidadSelect.append(option);
                        });
                    },
                    error : function(result){
                        resolve(result)
                    }
                });
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