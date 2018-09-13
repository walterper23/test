@extends('app.layoutMaster')

@section('title', title('Inicio') )

@section('content')
    <!-- Row #2 -->
    {{--
    @can('REC.DOCUMENTO.LOCAL')
    <div class="{{ sizeof($recepcion_local) ? 'col-md-4' : 'col-md-3' }} seccion-notificacion">
        <div class="block block-themed">
            <div class="block-header bg-corporate">
                <h3 class="block-title">
                    Recepción local <small></small>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content notificacion" style="padding:5px;" @if(sizeof($recepcion_local)) data-toggle="slimscroll" data-height="180px" data-rail-visible="true" data-color="#999" data-rail-color="#eee" data-opacity="1" data-always-visible="true" @endif>
                @foreach( $recepcion_local as $notificacion )
                    @include( $notificacion -> NOTI_URL ? 'Dashboard.notificacion_url' : 'Dashboard.notificacion' )
                @endforeach
            </div>
        </div>
    </div>
    @endcan

    @if( user() -> canAtLeast('REC.DOCUMENTO.FORANEO','REC.VER.FORANEO') )
    @endcan
    
    @can('SEG.PANEL.TRABAJO')
    @endcan

    @can('SEG.ADMIN.SEMAFORO')
    @endcan
    --}}
    <!-- END Row #2 -->

<div class="row gutters-tiny justify-content-center">
    @can('REPO.VER.GRA.DIA.SEM')
    <div class="col-md-6 col-sm-12 invisible" data-toggle="appear">
        <!-- Bars Chart -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Documentos recibidos hoy <b>[ {{ $fecha_documentos_recibidos_hoy }} ]</b></h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full text-center">
                <!-- Bars Chart Container -->
                <canvas id="js-chartjs-bars-1"></canvas>
            </div>
        </div>
        <!-- END Bars Chart -->
    </div>
    @endcan
    
    @can('REPO.VER.GRA.DIA.SEM')
    <div class="col-md-6 col-sm-12 invisible" data-toggle="appear">
        <!-- Bars Chart -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Documentos recibidos en la semana <b>[ {{ $fecha_documentos_semana }} ]</b></h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full text-center">
                <!-- Bars Chart Container -->
                <canvas id="js-chartjs-bars-2"></canvas>
            </div>
        </div>
        <!-- END Bars Chart -->
    </div>
    @endcan

    @can('REPO.VER.GRA.MEN.ANUAL')
    <div class="col-md-4 col-sm-12 invisible" data-toggle="appear">
        <!-- Pie Chart -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Total documentos mes actual</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full text-center">
                <!-- Pie Chart Container -->
                <canvas id="js-chartjs-pie-1"></canvas>
            </div>
        </div>
        <!-- END Pie Chart -->
    </div>
    @endcan

    @can('REPO.VER.GRA.MEN.ANUAL')
    <div class="col-md-4 col-sm-12 invisible" data-toggle="appear">
        <!-- Pie Chart -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Total documentos {{ date('Y') }}</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full text-center">
                <!-- Pie Chart Container -->
                <canvas id="js-chartjs-pie-2"></canvas>
            </div>
        </div>
        <!-- END Pie Chart -->
    </div>
    @endcan
</div>
@endsection

@push('js-script')
{{ Html::script('js/plugins/chartjs/Chart.bundle.min.js') }}
{{ Html::script('js/pages/be_comp_charts_dashboard.js') }}
{{ Html::script('js/helpers/dashboard.helper.js') }}
@endpush

@push('js-custom')
<script>
    var areas_notificaciones = $('div.notificacion');
    var notificaciones = $('button.cerrar-notificacion');

    notificaciones.on('click', function(e){

        e.preventDefault();

        var el = $(this);

        App.ajaxRequest({
            url  : '{{ url('manager') }}',
            data : { action : 'eliminar-notificacion', id : el.data('notificacion') },
            success : function( result ){
                // location.reload();
            },
            complete : function(){
                setTimeout(consultarNotificaciones,150);
            }
        });

    })

    setTimeout(consultarNotificaciones,150);
    // consultarNotificaciones();

    function consultarNotificaciones(){
        areas_notificaciones.each(function(index,element){
            if (! $(element).html().trim().length )
            {
                $(element).html('<p class="text-center text-muted font-size-xs mb-10">- No hay notificaciones -</p>');
                $(element).css('height','').parent().css('height','').closest('.seccion-notificacion').removeClass('col-md-4').addClass('col-md-3');
            }
        });
    }
</script>
@endpush