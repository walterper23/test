@extends('app.layoutMaster')

@section('title')
	{{ title('Seguimiento de documento') }}
@endsection

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="{{ url() -> previous() }}"><i class="fa fa-server"></i> Panel de trabajo</a>
        <a class="breadcrumb-item" href="{{ url() -> previous() }}">Documentos</a>
        <a class="breadcrumb-item" href="javascript:void(0)">Documento # {{ $documento -> getCodigo() }}</a>
        <span class="breadcrumb-item active">Seguimiento # {{ $seguimiento -> getCodigo(5) }}</span>
    </nav>
@endsection

@section('content')
	<!-- Timeline Activity -->
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title"><i class="fa fa-fw fa-history"></i> Historial de Cambios de Estados del Documento</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                <!--button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button-->
            </div>
        </div>
        <div class="block-content block-content-full">
            <ul class="list list-timeline list-timeline-modern pull-t">
                @foreach( $seguimientos as $seguimiento )
                <li>
                    <div class="list-timeline-time">
                        <i class="fa fa-fw fa-calendar"></i> {{ $seguimiento -> presenter() -> getFechaSeguimiento() }}
                    </div>
                    <i class="list-timeline-icon fa fa-flash bg-danger"></i>
                    <div class="list-timeline-content">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="font-w600">
                                    <span class="text-primary"># {{ $seguimiento -> getCodigo(5) }}</span>
                                    {{ $seguimiento -> EstadoDocumento -> getNombre() }}
                                </p>
                                <i class="fa fa-fw fa-comment-o"></i> <span class="font-w600">Observaciones:</span> {{ $seguimiento -> getObservacion() }}
                                <div class="font-size-sm text-muted"><i class="fa fa-fw fa-user"></i> {{ trim(sprintf('%s %s',$seguimiento -> USDE_NOMBRES,$seguimiento -> USDE_APELLIDOS)) }}</div>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <a href="javascript:void(0)">
                                        <i class="fa fa-fw fa-sitemap"></i> {{ $seguimiento -> DireccionOrigen -> getNombre() }}
                                    </a>
                                </p>
                                <p>
                                    @if (! is_null($seguimiento -> DepartamentoOrigen))
                                    <a href="javascript:void(0)">
                                        <i class="fa fa-fw fa-sitemap"></i> {{ $seguimiento -> DepartamentoOrigen -> getNombre() }}
                                    </a>
                                    @else
                                        <div class="text-muted"><i class="fa fa-fw fa-sitemap"></i></div>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <!-- END Timeline Activity -->
@endsection

@push('js-script')
    {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">


</script>
@endpush