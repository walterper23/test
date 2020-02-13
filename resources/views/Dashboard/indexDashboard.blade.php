@extends('app.layoutMaster')

@section('title', title('Inicio') )

@section('content')
<div class="row gutters-tiny justify-content-center">
    @can('REPO.VER.GRA.DIA.SEM')
    <div class="col-md-6 col-sm-12 invisible" data-toggle="appear">
        <!-- Bars Chart -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Documentos recibidos hoy, {{ $fecha_documentos_recibidos_hoy }}</h3>
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
                <h3 class="block-title">Documentos recibidos en la semana, {{ $fecha_documentos_semana }}</h3>
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
                <h3 class="block-title">Total documentos del mes de {{ $mes_actual . date(' Y') }}</h3>
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