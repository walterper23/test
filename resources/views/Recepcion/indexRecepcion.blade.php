@extends('app.layoutMaster')

@section('title')
	{{ title('Documentos recepcionados') }}
@endsection

@push('css-style')
    {{ Html::style('js/plugins/datatables/dataTables.bootstrap4.min.css') }}
    {{ Html::style('js/plugins/datatables/buttons1.4.2/css/datatables.buttons.bootstrap4.min.css') }}
    {{ Html::style('js/plugins/sweetalert2/sweetalert2.min.css') }}
@endpush

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-home"></i> Recepci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('recepcion/documentos') }}">Documentos</a>
        <span class="breadcrumb-item active">Recepcionados</span>
    </nav>
@endsection

@section('content')
<div class="block block-themed block-mode-loading-refresh">
    <ul class="nav nav-tabs nav-tabs-alt nav-tabs-block align-items-center" data-toggle="tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link{{ $tab_1 }}" href="#btabswo-static-one">Denuncias</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $tab_2 }}" href="#btabswo-static-two">Doctos. denuncias</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $tab_3 }}" href="#btabswo-static-three">Documentos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $tab_4 }}" href="#btabswo-static-four">Recepciones foráneas</a>
        </li>
        <li class="nav-item ml-auto">
            <div class="block-options mr-15">
            <button type="button" class="btn-block-option d-none d-sm-inline" onclick="location.href='{{ url('recepcion/documentos/nueva-recepcion') }}'">
                <i class="fa fa-fw fa-plus"></i> Nueva recepción
            </button>
            <button type="button" class="btn-block-option d-none d-sm-inline" onclick="hRecepcion.reloadTables(['denuncias-datatables','documentos-denuncias-datatables','documentos-datatables'])">
                <i class="fa fa-fw fa-refresh"></i> Actualizar
            </button>
            <div class="dropdown">
                <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Opciones</button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="location.href='{{ url('recepcion/documentos/nueva-recepcion') }}'">
                        <i class="fa fa-fw fa-plus mr-5"></i>Nueva recepción
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" onclick="hRecepcion.reloadTables(['denuncias-datatables','documentos-denuncias-datatables','documentos-datatables'])">
                        <i class="fa fa-fw fa-refresh mr-5"></i>Actualizar registros
                    </a>
                </div>
            </div>
        </div>
        </li>
    </ul>
    <div class="block-content tab-content">
        <div class="tab-pane{{ $tab_1 }}" id="btabswo-static-one" role="tabpanel">
            <div class="table-responsive">
                {{ $table1 -> html() }}
            </div>
        </div>
        <div class="tab-pane{{ $tab_2 }}" id="btabswo-static-two" role="tabpanel">
            <div class="table-responsive">
                {{ $table2 -> html() }}
            </div>
        </div>
        <div class="tab-pane{{ $tab_3 }}" id="btabswo-static-three" role="tabpanel">
            <div class="table-responsive">
                {{ $table3 -> html() }}
            </div>
        </div>
        <div class="tab-pane{{ $tab_4 }}" id="btabswo-static-four" role="tabpanel">
            <div class="table-responsive">
                
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-script')
    {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
    {{ Html::script('js/plugins/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('js/plugins/datatables/dataTables.bootstrap4.min.js') }}
    {{ Html::script('js/helpers/recepcion.helper.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
@endpush

@push('js-custom')
    @if (isset($acuse))
    <script type="text/javascript">
    hRecepcion.verAcuse({{ $acuse }});
    @endif
    </script>

    {{ $table1 -> javascript() }}
    {{ $table2 -> javascript() }}
    {{ $table3 -> javascript() }}
@endpush