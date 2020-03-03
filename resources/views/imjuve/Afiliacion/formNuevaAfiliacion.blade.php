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
            @if($action==2)
                <input type="hidden" value="{{$id}}" name="id">
            @endif
            <div class="block-content row">
                <div class="col-md-8">
                    {!! Field::text('nombres',$modelo->getNombres(),['label'=>'Nombre(s)','required','maxlength'=>255]) !!}
                    {!! Field::text('paterno',$modelo->getPaterno(),['label'=>'A. Paterno','required','maxlength'=>255]) !!}
                    {!! Field::text('materno',$modelo->getMaterno(),['label'=>'A. Materno','required','maxlength'=>255]) !!}
                    {!! Field::select('genero',$modelo->getGenero(),['label'=>'Género','required'],$generos) !!}
                    {!! Field::datepicker('nacimiento',$modelo->getFechaNacimiento(),['label'=>'F. Nacimiento','required','placeholder'=>date('Y-m-d'),'popover'=>['Fecha de Nacimiento','Introduzca la fecha de nacimiento del afiliado']]) !!}
                </div>
                <div class="col-md-7">
                    {!! Field::select('escolaridad',$modelo->getEscolaridad(),['label'=>'Escolaridad','class'=>'js-select2 maxwidth',],$escolaridades) !!}
                    {!! Field::select('nacionalidad','',['label'=>'Nacionalidad','class'=>'js-select2 maxwidth'],$nacionalidades) !!}
                </div>
                <div class="col-md-5">
                    {!! Field::select('ecivil',$modelo->getEcivil(),['label'=>'E. Civil','class'=>'js-select2 maxwidth'],$estados_civiles) !!}
                    {!! Field::select('ocupacion',$modelo->getOcupacion(),['label'=>'Ocupación','class'=>'js-select2 maxwidth'],$ocupaciones) !!}
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
                <div class="col-md-6">
                    {!! Field::text('telefono',$modelo->getTelefono(),['label'=>'Télefono','maxlength'=>10]) !!}
                </div>
                <div class="col-md-6">
                    {!! Field::text('correo',$modelo->getCorreo(),['label'=>'Correo','maxlength'=>255]) !!}
                </div>
                <div class="col-md-6 form-group row">
                    <label for="cp" class="col-md-5 col-form-label">Código Postal</label>
                    <div class="col-md-7">
                        <div class="input-group">
                            <input maxlength="5" id="cp" class="form-control" name="cp" type="text" value="{{(!is_null($modelo->Direccion))?$modelo->Direccion->getCp():''}}">
                            <div class="input-group-appen">
                                <button type="button" class="btn btn-secondary">
                                    <i class="si si-refresh"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Field::select('entidad',((!is_null($modelo->Direccion))?($modelo->Direccion->getEntidad()):''),['label'=>'Entidad','class'=>'js-select2 maxwidth',],$entidades) !!}
                </div>
                <div class="col-md-6">
                    {!! Field::select('municipio','',['label'=>'Municipio','class'=>'js-select2 maxwidth',],[]) !!}
                </div>
                <div class="col-md-6">
                    {!! Field::select('localidad','',['label'=>'Localidad','class'=>'js-select2 maxwidth',],[]) !!}
                </div>
                <div class="col-md-12">
                    {!! Field::select('asentamiento','',['label'=>'Colonia/Asentamiento','class'=>'js-select2 maxwidth',],[]) !!}
                </div>
                <div class="col-md-6">
                    {!! Field::select('tvialidad',((!is_null($modelo->Direccion))?($modelo->Direccion->getTvialidad()):''),['label'=>'Vialidad','class'=>'js-select2 maxwidth',],$vialidades) !!}
                </div>
                <div class="col-md-6">
                    {!! Field::text('vialidad',(!is_null($modelo->Direccion))?$modelo->Direccion->getVialidad():'',['label'=>'Nombre','maxlength'=>255]) !!}
                </div>
                <div class="col-md-6">
                    {!! Field::text('next',(!is_null($modelo->Direccion))?$modelo->Direccion->getNext():'',['label'=>'Num Ext./Mza','maxlength'=>20]) !!}
                </div><div class="col-md-6">
                    {!! Field::text('nint',(!is_null($modelo->Direccion))?$modelo->Direccion->getNint():'',['label'=>'Num Int./Lt','maxlength'=>20]) !!}
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
            var asentamientoSelect = $("#asentamiento");
            var changeEntidad = this.form.find('#entidad').on('change',function(e){
                municipioSelect.val(null).trigger('change');
                 App.ajaxRequest({
                    url   : '/imjuve/utils/municipios',
                    type  : 'POST',
                    data  : {'entidad':e.currentTarget.value},
                    success : function(result){
                        //municipioSelect.select2('destroy');
                        municipioSelect.select2('destroy').off('select2:select');
                        municipioSelect.select2();
                        console.log('hola');
                        $.each(result, function(i, item) {
                            var option = new Option(i,item, true, true);
                            municipioSelect.append(option);
                        });
                        municipioSelect.trigger('change');
                    },
                    error : function(result){
                        resolve(result)
                    }
                });
            });
            var changeMunicipio = this.form.find('#municipio').on('change',function(e){
                if(e.currentTarget.value > 0 && entidadSelect.val() > 0){
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
                }
            });
            var changeLocalidad = this.form.find('#localidad').on('change',function(e){
                 App.ajaxRequest({
                    url   : '/imjuve/utils/asentamientos',
                    type  : 'POST',
                    data  : {'entidad':entidadSelect.val(),'municipio':municipioSelect.val(),'localidad':e.currentTarget.value},
                    success : function(result){
                        $.each(result, function(i, item) {
                            var option = new Option(i,item, true, true);
                            asentamientoSelect.append(option);
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
                nombres : { required : true, minlength : 1, maxlength : 255 },
                paterno : { required : true, minlength : 1, maxlength : 255 },
                materno : { required : true, minlength : 1, maxlength : 255 },
                genero : { required : true },
                nacimiento : { required : true },

			}
		}

		this.messages = function(){
			return {
                nombres : {
                    required : 'Introduzca los nombre(s) del afiliado',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                paterno : {
                    required : 'Introduzca apellido paterno del afiliado',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                materno : {
                    required : 'Introduzca apellido materno del afiliado',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                genero : {
                    required : 'Seleccione un género'
                },
                nacimiento : {
                    required : 'Ingrese la fecha de nacimiento'
                },

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