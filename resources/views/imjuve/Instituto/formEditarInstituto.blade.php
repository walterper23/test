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
    @if($action==2)
    {{ Form::hidden('id',$modelo->ORGA_ID) }}
    @endif
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
                        <input required="" maxlength="5" id="cp" class="form-control" name="cp" type="text" value="{{(!is_null($modelo->Direccion))?$modelo->Direccion->getCp():''}}">
                        <div class="input-group-appen">
                            <button type="button" class="btn btn-secondary">
                                <i class="si si-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
         
            <div class="col-md-6">
                {!! Field::select('entidad',((!is_null($modelo->Direccion))?($modelo->Direccion->getEntidad()):''),['id'=>'entidadEdit','label'=>'Entidad','class'=>'js-select2 maxwidth',],$entidades) !!}
            </div>
            <div class="col-md-6">
                {!! Field::select('municipio','',['id'=>'municipioEdit','label'=>'Municipio','class'=>'js-select2 maxwidth',],[]) !!}
            </div>
            <div class="col-md-6">
                {!! Field::select('localidad','',['id'=>'localidadEdit','label'=>'Localidad','class'=>'js-select2 maxwidth',],[]) !!}
            </div>
            <div class="col-md-12">
                {!! Field::select('asentamiento','',['id'=>'asentamientoEdit','label'=>'Colonia/Asentamiento','class'=>'js-select2 maxwidth',],[]) !!}
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
    var entidadSelect   = $("#entidadEdit");
                var municipioSelect = $("#municipioEdit");
                var localidadSelect = $("#localidadEdit");
                var asentamientoSelect = $("#asentamientoEdit");
                var changeEntidad = this.form.find('#entidadEdit').on('change',function(e){
                    municipioSelect.val(null).trigger('change');
                    App.ajaxRequest({
                        url   : '/imjuve/utils/municipios',
                        type  : 'POST',
                        data  : {'entidad':e.currentTarget.value},
                        success : function(result){
                            //municipioSelect.select2('destroy');
                            municipioSelect.select2('destroy').off('select2:select');
                            municipioSelect.select2();
                            console.log('holadssxd');
                            $.each(result, function(i, item) {
                                var option = new Option(i,item, true, true);
                                municipioSelect.select2().append(option);
                                console.log('heyt');
                            });
                            //municipioSelect.trigger('change');
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
                var changeMunicipio = this.form.find('#municipioEdit').on('change',function(e){
                    if(e.currentTarget.value > 0 && e.currentTarget.value!=999 && entidadSelect.val() > 0){
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
                var changeLocalidad = this.form.find('#localidadEdit').on('change',function(e){
                    let entidad     = entidadSelect.val();
                    let municipio   = municipioSelect.val();
                    let localidad   = e.currentTarget.value;
                    if(entidad>0 && municipio>0 && localidad>0){
                        App.ajaxRequest({
                            url   : '/imjuve/utils/asentamientos',
                            type  : 'POST',
                            data  : {'entidad':entidad,'municipio':municipio,'localidad':localidad},
                            success : function(result){
                                $.each(result, function(i, item) {
                                    var option = new Option(i,item, true, true);
                                    asentamientoSelect.append(option);
                                });
                                @if($action==2)
                                if('{{$modelo->Direccion->getEntidad()}}'==entidad
                                    && '{{$modelo->Direccion->getMunicipio()}}'==municipio
                                    && '{{$modelo->Direccion->getLocalidad()}}'==localidad){
                                    asentamientoSelect.val('').val({{$modelo->Direccion->getAsentamiento()}}).change();
                                }
                                @endif

                            },
                            error : function(result){
                                resolve(result)
                            }
                        });
                    }
                });
    @if($action==2)
        entidadSelect.val('{{$modelo->Direccion->getEntidad()}}').change();
    @endif

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