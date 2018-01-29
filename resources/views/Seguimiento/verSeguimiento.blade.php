@extends('Tema.app')

@section('title')
	SIGESD :: Seguimiento de documento
@endsection

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-home"></i> Recepci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('recepcion/documentos') }}">Documentos</a>
        <a class="breadcrumb-item" href="{{ url('recepcion/documentos') }}">Ver seguimiento</a>
        <span class="breadcrumb-item active">{{ $documento->DOCU_NUMERO_OFICIO }}</span>
    </nav>
@endsection

@section('content')
	<!-- Timeline Activity -->
    <h2 class="content-heading">{{ $documento->DOCU_NUMERO_OFICIO }} <small>Seguimiento del documento</small></h2>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Historial de Estados del Documento</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content">
            <ul class="list list-activity">
                @foreach( $documento->seguimientos()->orderBy('SEGU_SEGUIMIENTO','DESC')->get() as $seguimiento )
                <li>
                    <i class="si si-pencil text-info"></i>
                    <div><b>{{ $seguimiento->estadoDocumento->ESDO_NOMBRE }}</b></div>
                    <div><span class="font-w600">Observaciones:</span> {{ $seguimiento->SEGU_OBSERVACION }}</div>
                    <div>
                        <a href="javascript:void(0)">
                            <i class="fa fa-sitemap"></i> {{ $seguimiento->direccion->DIRE_NOMBRE }}
                        </a>
                    </div>
                    <div>
                        <a href="javascript:void(0)">
                            <i class="fa fa-sitemap"></i> {{ $seguimiento->departamento->DEPA_NOMBRE }}
                        </a>
                    </div>
                    <div class="font-size-xs text-muted">{{ $seguimiento->SEGU_CREATED_AT }}</div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <!-- END Timeline Activity -->
@endsection

@push('js-script')
    {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
    {{ Html::script('js/helpers/recepcion.helper.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">



</script>
@endpush