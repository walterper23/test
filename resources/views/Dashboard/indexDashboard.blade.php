@extends('app.layoutMaster')

@section('title')
	{{ title('Inicio') }}
@endsection

@section('content')
<div class="row gutters-tiny">
    <!-- Row #2 -->
    <div class="col-md-6">
        <div class="block block-themed block-mode-loading-refresh">
            <div class="block-header bg-corporate">
                <h3 class="block-title">
                    Recepción local <small></small>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                @forelse( $recepcion_local as $notificacion )
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-{{ $notificacion -> getColor() }} alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="mb-0">
                                <span class="font-size-xs text-muted""><i>{{ $notificacion -> getFechaCreacion() }}</i></span>
                                <i class="fa fa-fw fa-angle-double-right"></i> {{ $notificacion -> getContenido() }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-md mb-10">No hay notificaciones</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="block block-themed block-mode-loading-refresh">
            <div class="block-header bg-flat-dark">
                <h3 class="block-title">
                    Recepción foránea <small></small>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                @forelse( $recepcion_foranea as $notificacion )
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-{{ $notificacion -> getColor() }} alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="mb-0">
                                <span class="font-size-xs text-muted""><i>{{ $notificacion -> getFechaCreacion() }}</i></span>
                                <i class="fa fa-fw fa-angle-double-right"></i> {{ $notificacion -> getContenido() }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-md mb-10">No hay notificaciones</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="block block-themed block-mode-loading-refresh">
            <div class="block-header bg-primary">
                <h3 class="block-title">
                    Panel de Trabajo <small></small>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                @forelse( $panel_trabajo as $notificacion )
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-{{ $notificacion -> getColor() }} alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="mb-0">
                                <span class="font-size-xs text-muted""><i>{{ $notificacion -> getFechaCreacion() }}</i></span>
                                <i class="fa fa-fw fa-angle-double-right"></i> {{ $notificacion -> getContenido() }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-md mb-10">No hay notificaciones</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="block block-themed block-mode-loading-refresh">
            <div class="block-header bg-earth">
                <h3 class="block-title">
                    Semaforización de documentos <small></small>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                @forelse( $semaforizacion as $notificacion )
                <div class="row">
                        <div class="alert alert-{{ $notificacion -> getColor() }} alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="mb-0">
                                <span class="font-size-xs text-muted""><i>{{ $notificacion -> getFechaCreacion() }}</i></span>
                                <i class="fa fa-fw fa-angle-double-right"></i> {{ $notificacion -> getContenido() }}
                            </p>
                        </div>
                </div>
                @empty
                <p class="text-muted text-md mb-10">No hay notificaciones</p>
                @endforelse
            </div>
        </div>
    </div>
    <!-- END Row #2 -->
    
    <div class="col-md-4 invisible" data-toggle="appear">
        <!-- Bars Chart -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Documentos recibidos hoy</h3>
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
    <div class="col-md-4 invisible" data-toggle="appear">
        <!-- Bars Chart -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Documentos recibidos en la semana</h3>
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
    <div class="col-md-4 invisible" data-toggle="appear">
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
    <div class="col-md-4 invisible" data-toggle="appear">
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
</div>
@endsection

@push('js-script')
{{ Html::script('js/plugins/chartjs/Chart.bundle.min.js') }}
{{ Html::script('js/pages/be_comp_charts_dashboard.js') }}
@endpush