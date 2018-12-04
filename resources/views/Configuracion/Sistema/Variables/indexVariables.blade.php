@extends('app.layoutMaster')

@section('title', title('Configuración de variables') )

@include('vendor.plugins.datepicker')
@include('vendor.plugins.select2')

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('configuracion/usuarios') }}">Sistema</a>
        <span class="breadcrumb-item active">Variables</span>
    </nav>
@endsection

@section('content')
    <div class="block block-themed block-mode-loading-refresh" id="context-{{ $form_id }}">
        <div class="block-header bg-pulse">
            <h3 class="block-title"><i class="fa fa-fw fa-cogs mr-5"></i> Configuración de variables</h3>
        </div>
        <ul class="nav nav-tabs nav-tabs-alt nav-tabs-block align-items-center" data-toggle="tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#btabswo-static-one">Sistema</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#btabswo-static-two">Institución</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#btabswo-static-three">Recepción</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" href="#btabswo-static-four">Logos & imágenes</a>
            </li> --}}
            <li class="nav-item">
                <a class="nav-link" href="#btabswo-static-five">Adicional</a>
            </li>
        </ul>
        {{ Form::open(['url'=>$url_send_form,'id'=>$form_id,'method'=>'POST']) }}
        {{ Form::hidden('action',1) }}
        <div class="block-content tab-content">
            <div class="tab-pane active" id="btabswo-static-one" role="tabpanel">
                <div class="col-md-6">
                    {!! Field::text('var1',$var[1]->getValor(),['label'=>$var[1]->getNombre(),'popover'=>[$var[1]->getNombre(),$var[1]->getDescripcion()],'labelWidth'=>'col-md-5','width'=>'col-md-7']) !!}
                    {!! Field::text('var2',$var[2]->getValor(),['label'=>$var[2]->getNombre(),'popover'=>[$var[2]->getNombre(),$var[2]->getDescripcion()],'labelWidth'=>'col-md-5','width'=>'col-md-7']) !!}
                    {!! Field::text('var14',$var[14]->getValor(),['label'=>$var[14]->getNombre(),'popover'=>[$var[14]->getNombre(),$var[14]->getDescripcion()],'labelWidth'=>'col-md-5','width'=>'col-md-7']) !!}
                </div>
            </div>
            <div class="tab-pane" id="btabswo-static-two" role="tabpanel">
                <div class="col-md-6">
                    {!! Field::text('var3',$var[3]->getValor(),['label'=>$var[3]->getNombre(),'popover'=>[$var[3]->getNombre(),$var[3]->getDescripcion()]]) !!}
                    {!! Field::text('var4',$var[4]->getValor(),['label'=>$var[4]->getNombre(),'popover'=>[$var[4]->getNombre(),$var[4]->getDescripcion()]]) !!}
                    {!! Field::textarea('var5',$var[5]->getValor(),['label'=>$var[5]->getNombre(),'popover'=>[$var[5]->getNombre(),$var[5]->getDescripcion()],'size'=>'20x4','noresize']) !!}
                    {!! Field::text('var6',$var[6]->getValor(),['label'=>$var[6]->getNombre(),'popover'=>[$var[6]->getNombre(),$var[6]->getDescripcion()]]) !!}
                </div>
            </div>
            <div class="tab-pane" id="btabswo-static-three" role="tabpanel">
                <div class="col-md-6">
                    {!! Field::select('var9',$var[9]->getValor(),['label'=>$var[9]->getNombre(),'popover'=>[$var[9]->getNombre(),$var[9]->getDescripcion()],'required','labelWidth'=>'col-md-5','width'=>'col-md-7'],$direcciones) !!}
                    {!! Field::select('var10',$var[10]->getValor(),['label'=>$var[10]->getNombre(),'popover'=>[$var[10]->getNombre(),$var[10]->getDescripcion()],'labelWidth'=>'col-md-5','width'=>'col-md-7'],$departamentos) !!}
                    {!! Field::select('var11',$var[11]->getValor(),['label'=>$var[11]->getNombre(),'popover'=>[$var[11]->getNombre(),$var[11]->getDescripcion()],'required','labelWidth'=>'col-md-5','width'=>'col-md-7'],$direcciones) !!}
                    {!! Field::select('var12',$var[12]->getValor(),['label'=>$var[12]->getNombre(),'popover'=>[$var[12]->getNombre(),$var[12]->getDescripcion()],'labelWidth'=>'col-md-5','width'=>'col-md-7'],$departamentos) !!}
                    {!! Field::select('var15',$var[15]->getValor(),['label'=>$var[15]->getNombre(),'popover'=>[$var[15]->getNombre(),$var[15]->getDescripcion()],'required','labelWidth'=>'col-md-5','width'=>'col-md-7','placeholder'=>false],$estados_documentos) !!}
                </div>
            </div>
            <div class="tab-pane" id="btabswo-static-four" role="tabpanel">
                <div class="col-md-6">
                    
                </div>
            </div>
            <div class="tab-pane" id="btabswo-static-five" role="tabpanel">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="var16" class="col-md-5 col-form-label">{{ $var[16]->getNombre() }}<i class="fa fa-fw fa-question-circle text-info" data-toggle="popover" title="{{ $var[16]->getNombre() }}" data-placement="right" data-content="{{ $var[16]->getDescripcion() }}" data-original-title="Días Límite Semáforo"></i></label>
                        @php
                            $valor = toBoolean( $var[16]->getValor() );
                            $checked1 = $valor ? 'checked' : '';
                            $checked2 = !$valor ? 'checked' : '';
                        @endphp
                        <div class="col-md-7 text-center">
                            <label class="css-control css-control-md css-control-danger css-radio">
                                <input type="radio" class="css-control-input" name="var16" value="on" {{ $checked1 }}>
                                <span class="css-control-indicator"></span> Activado
                            </label>
                            <label class="css-control css-control-md css-control-secondary css-radio">
                                <input type="radio" class="css-control-input" name="var16" value="off" {{ $checked2 }}>
                                <span class="css-control-indicator"></span> Desactivado
                            </label>
                        </div>
                    </div>
                    {!! Field::text('var13',$var[13]->getValor(),['label'=>$var[13]->getNombre(),'popover'=>[$var[13]->getNombre(),$var[13]->getDescripcion()],'labelWidth'=>'col-md-5','width'=>'col-md-7']) !!}
                    {!! Field::datepicker('var17',$var[17]->getValor(),['label'=>$var[17]->getNombre(),'popover'=>[$var[17]->getNombre(),$var[17]->getDescripcion()],'labelWidth'=>'col-md-5','width'=>'col-md-7','placeholder'=>date('Y-12-31')]) !!}
                    {!! Field::text('var18',$var[18]->getValor(),['label'=>$var[18]->getNombre(),'popover'=>[$var[18]->getNombre(),$var[18]->getDescripcion()],'labelWidth'=>'col-md-5','width'=>'col-md-7']) !!}
                    {!! Field::datepicker('var19',$var[19]->getValor(),['label'=>$var[19]->getNombre(),'popover'=>[$var[19]->getNombre(),$var[19]->getDescripcion()],'labelWidth'=>'col-md-5','width'=>'col-md-7','placeholder'=>date('Y-12-31')]) !!}
                    {!! Field::text('var20',$var[20]->getValor(),['label'=>$var[20]->getNombre(),'popover'=>[$var[20]->getNombre(),$var[20]->getDescripcion()],'labelWidth'=>'col-md-5','width'=>'col-md-7']) !!}

                </div>
            </div>
        </div>
        {{ Form::close() }}
        <div class="block-content block-content-full block-content-sm bg-body-light">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button class="btn btn-default" id="btn-cancel"><i class="fa fa-fw fa-times text-danger"></i> Cancelar</button>
                    <button class="btn btn-primary" id="btn-ok"><i class="fa fa-fw fa-floppy-o"></i> Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js-script')
    {{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">
    'use strict';
    $.extend(new AppForm, new function(){

        this.context_   = '#context-{{ $form_id }}';
        this.form_      = '#{{$form_id}}';
        this.btnOk_     = '#btn-ok';
        this.btnCancel_ = '#btn-cancel';

        this.start = function(){
            Codebase.helpers(['datepicker','select2']);

            var self = this;

            var var16 = $('#var16');
            console.log(var16)

        };

        this.successSubmitHandler = function( result ){
            var config = {
                type: result.type,
                message : result.message
            };

            if( result.status ){
                config.onShown = function(){
                    location.reload();
                }
            }

            AppAlert.notify(config);
        };

        this.rules = function(){
            return {

            }
        };

        this.messages = function(){
            return {
                
            }
        };

    }).init().start();
</script>
@endpush