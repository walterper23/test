@extends('vendor.modal.template',['headerColor'=>($type==1)?'bg-primary':'bg-success'])

@section('title')<i class="fa fa-fw fa-flag"></i> {!! $title !!}@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        @if ($type == 1)
        <p class="font-w700">Solicitud realizada:</p>
        <p>{{ $semaforo -> getSolicitud() }}</p>
        @else
        <p class="font-w700">Respuesta a la solicitud:</p>
        <p>{{ $semaforo -> getRespuesta() }}</p>
        @endif
    </div>
    <div class="col-md-6">
        <span class="font-w700 text-primary-darker" title="Seguimiento #{{ $seguimiento -> getCodigo() }}">
            <span class="text-danger"><i class="fa fa-fw fa-flash"></i> #{{ $seguimiento -> getCodigo() }}</span> :: {{ $seguimiento -> EstadoDocumento -> getNombre() }}
        </span>
        <p><span class="font-w600"><i class="fa fa-fw fa-comment-o"></i> Observaciones:</span> {{ $seguimiento -> getObservacion() }}</p>
        @if( $seguimiento -> Documento -> enSeguimiento() )
        <p><span class="font-w600"><i class="fa fa-fw fa-street-view"></i> Instrucción al destino:</span> {{ $seguimiento -> getInstruccion() }}</p>
        @endif
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-md-6">
                <hr>
                <div class="font-size-sm text-muted"><i class="fa fa-fw fa-sitemap"></i> {{ $seguimiento -> DireccionOrigen -> getNombre() }}</div>
                <div class="font-size-sm text-muted"><i class="fa fa-fw fa-sitemap"></i> {{ optional($seguimiento -> DepartamentoOrigen) -> getNombre() }}</div>
            </div>
            <div class="col-md-6">
                <hr>
                <div class="font-size-sm text-muted"><i class="fa fa-fw fa-user"></i> {{ $seguimiento_usuario }}</div>
                <div class="font-size-sm text-muted"><i class="fa fa-fw fa-calendar"></i> {{ $seguimiento -> presenter() -> getFechaSeguimiento() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-custom')
<script type="text/javascript">
    'use strict';

    var modalVerSeguimiento = new AppForm;
    $.extend(modalVerSeguimiento, new function(){
        this.context_ = '#modal-ver-seguimiento';
    }).init();
</script>
@endpush