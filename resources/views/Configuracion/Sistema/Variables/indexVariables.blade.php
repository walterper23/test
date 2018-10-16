@extends('app.layoutMaster')

@section('title', title('Configuración de variables') )

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
            <li class="nav-item">
                <a class="nav-link" href="#btabswo-static-four">Logos & imágenes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#btabswo-static-five">Adicional</a>
            </li>
        </ul>
        {{ Form::open(['url'=>$url_send_form,'id'=>$form_id,'method'=>'POST']) }}
        {{ Form::hidden('action',1) }}
        <div class="block-content tab-content">
            <div class="tab-pane active" id="btabswo-static-one" role="tabpanel">
                <div class="col-md-8">
                    @foreach([1,2,14] as $key)
                    @php $variable = $var[ $key ]; @endphp
                    {!! Field::text('var' . $key,$variable->getValor(),['label'=>$variable->getNombre(),'popover'=>[$variable->getNombre(),$variable->getDescripcion()]]) !!}
                    @endforeach
                </div>
            </div>
            <div class="tab-pane" id="btabswo-static-two" role="tabpanel">
                <div class="col-md-8">
                    @foreach([3,4,5,6] as $key)
                    @php $variable = $var[ $key ]; @endphp
                    {!! Field::text('var' . $key,$variable->getValor(),['label'=>$variable->getNombre(),'popover'=>[$variable->getNombre(),$variable->getDescripcion()]]) !!}
                    @endforeach
                </div>
            </div>
            <div class="tab-pane" id="btabswo-static-three" role="tabpanel">
                <div class="col-md-8">
                    @foreach([9,10,11,12,15] as $key)
                    @php $variable = $var[ $key ]; @endphp
                    {!! Field::text('var' . $key,$variable->getValor(),['label'=>$variable->getNombre(),'popover'=>[$variable->getNombre(),$variable->getDescripcion()]]) !!}
                    @endforeach
                </div>
            </div>
            <div class="tab-pane" id="btabswo-static-four" role="tabpanel">
                <div class="col-md-8">
                    
                </div>
            </div>
            <div class="tab-pane" id="btabswo-static-five" role="tabpanel">
                <div class="col-md-8">
                    @foreach([13] as $key)
                    @php $variable = $var[ $key ]; @endphp
                    {!! Field::text('var' . $key,$variable->getValor(),['label'=>$variable->getNombre(),'popover'=>[$variable->getNombre(),$variable->getDescripcion()]]) !!}
                    @endforeach
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
            Codebase.helper('select2');

            var self = this;
            
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
                usuario : { required : true }
            }
        };

        this.messages = function(){
            return {
                usuario : { required : 'Especifique un usuario' }
            }
        };

    }).init().start();
</script>
@endpush