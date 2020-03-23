@extends('vendor.modal.template',['headerColor'=>'bg-earth'])

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
            <h3 class="block-title">Datos del organismo</h3>
        </div>
        <div class="block-content row">


            <div class="col-md-8">
                {!! Field::text('organismo',$modelo->getAlias(),['label'=>'Nombre del organismo','required','maxlength'=>255]) !!}
                {!! Field::text('razon',$modelo->getRazonSocial(),['label'=>'Razon Social','required','maxlength'=>255]) !!}
                {!! Field::text('telefono',$modelo->getTelefono(),['label'=>'Telefono','required','maxlength'=>255]) !!}
            </div>
            <div class="col-md-4">
                <img src="/img/avatars/no-instituto.jpg" width="210" height="210" id="image-cropper-img">
                <button type="button" class="btn btn-success crop_image" name="button" id="crop-button">Seleccionar imagen</button>
                
                <input type="file" name="imagen" id="imagen" accept="image/x-png,image/jpeg,image/jpg" style="display:none;">
                <input type="hidden" name="dataX" id="dataX" value="">
                <input type="hidden" name="dataWidth" id="dataWidth" value="">
                <input type="hidden" name="dataY" id="dataY" value="">
                <input type="hidden" name="dataHeight" id="dataHeight" value="">

    
                
            </div>
        </div>
    </div>
</div>

       </div>

       <div class="block col-lg-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">Dirección y ubicacion</h3>
        </div>
        
        <div class="block-content row">
            <div class="col-md-6 form-group row">
                <label for="cp" class="col-md-5 col-form-label" required="">Código Postal</label>
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
                {!! Field::select('entidad','',['label'=>'Entidad','class'=>'js-select2 maxwidth',],$entidades) !!}
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
                {!! Field::select('tvialidad','',['label'=>'Vialidad','class'=>'js-select2 maxwidth',],$vialidades) !!}
            </div>

            <div class="col-md-6">
                {!! Field::text('vialidad','',['label'=>'Nombre','maxlength'=>255]) !!}
            </div>
            <div class="col-md-6">
                {!! Field::text('next','',['label'=>'Num Ext./Mza','maxlength'=>20]) !!}
            </div>
            <div class="col-md-6">
                {!! Field::text('nint','',['label'=>'Num Int./Lt','maxlength'=>20]) !!}
            </div>

            
        
        </div>
    </div>

    </div>

{{ Form::close() }}
@endsection

@push('js-script')
    {{ Html::script('js/helpers/imjuve/instituto.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">
    'use strict';

    var formInstituto = new AppForm;
	$.extend(formInstituto, new function(){

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
                        @if($action==2)
                            if('{{$modelo->Direccion->getEntidad()}}'==e.currentTarget.value){
                                municipioSelect.val('').val({{$modelo->Direccion->getMunicipio()}}).change();
                            }
                        @endif
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
                            @if($action==2)
                            if('{{$modelo->Direccion->getEntidad()}}'==entidadSelect.val()
                                && '{{$modelo->Direccion->getMunicipio()}}'==e.currentTarget.value){
                                localidadSelect.val('').val({{$modelo->Direccion->getLocalidad()}}).change();
                            }
                            @endif
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
                        @if($action==2)
                        if('{{$modelo->Direccion->getEntidad()}}'==entidadSelect.val()
                            && '{{$modelo->Direccion->getMunicipio()}}'==municipioSelect.val()
                            && '{{$modelo->Direccion->getLocalidad()}}'==e.currentTarget.value){
                            asentamientoSelect.val('').val({{$modelo->Direccion->getAsentamiento()}}).change();
                        }
                        @endif

                    },
                    error : function(result){
                        resolve(result)
                    }
                });
            });


          





            @if($action==2)
                entidadSelect.val('{{$modelo->Direccion->getEntidad()}}').change();
            @endif

          

            //funcion del cropper js
            var $cropper =  $('#image-cropper-img'),
                $image = $('#image-cropper-img'),
                $dataX = $('#dataX'),
                $dataY = $('#dataY'),
                $dataHeight = $('#dataHeight'),
                $dataWidth = $('#dataWidth'),
                options = {
                    aspectRatio: 1,
                    preview: '.preview',
                    crop: function(e) {
                        $dataX.val(Math.round(e.x));
                        $dataY.val(Math.round(e.y));
                        $dataHeight.val(Math.round(e.height));
                        $dataWidth.val(Math.round(e.width));
                    }
                };

                $('#imagen').on('change',function(e){
                    if(this.files && this.files[0]){
                        var reader = new FileReader();
                        reader.onload = function (e){
                            $cropper.cropper(options);
                            $cropper.cropper('replace', e.target.result);
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                $('#crop-button').click(function(){
                    $('#imagen').click();
                });

        };
        //metodo para poder mandar el objeto imagen
        this.submitHandler = function( form ){
            if(!$(form).valid()) return false;

            App.ajaxRequest({
                url         : $(form).attr('action'),
                data        : new FormData($(form)[0]),
                cache       : false,
                processData : false,
                contentType : false,
                beforeSend  : formInstituto.beforeSubmitHandler,
                success     : formInstituto.successSubmitHandler,
                code422     : formInstituto.displayErrors
            });
        };
            // fin de la funcion del cropper js

            this.rules = function(){
			return {
                organismo : { required : true, minlength : 1, maxlength : 255 },
                razon : { required : true, minlength : 1, maxlength : 255 },
                telefono : { required : true, minlength : 1, maxlength : 255 },
              

			}
		}

		this.messages = function(){
			return {
                organismo : {
                    required : 'Introduzca el Nombre del Instituto',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                razon : {
                    required : 'Introduzca la Razon Social del Instituto',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                telefono : {
                    required : 'Introduzca el Telefono del Instituto',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
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