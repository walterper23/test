@extends('app.layoutMaster')

@section('title', title('Documentos foráneos recepcionados') )

@include('vendor.plugins.datatables')

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-home"></i> Recepci&oacute;n foránea</a>
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
        <li class="nav-item ml-auto">
            <div class="block-options mr-15">
            @can('REC.DOCUMENTO.FORANEO')
            <button type="button" class="btn-block-option d-none d-sm-inline" onclick="location.href='{{ url('recepcion/documentos-foraneos/nueva-recepcion') }}'">
                <i class="fa fa-fw fa-plus"></i> Nueva recepción
            </button>
            @endcan
            <!--button type="button" class="btn-block-option" onclick="hRecepcionForanea.reload('dataTableBuilder')">
                <i class="fa fa-refresh"></i> Actualizar
            </button-->
            <div class="dropdown">
                <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Opciones</button>
                <div class="dropdown-menu dropdown-menu-right">
                    @can('REC.DOCUMENTO.FORANEO')
                    <a class="dropdown-item" href="{{ url('recepcion/documentos-foraneos/nueva-recepcion') }}">
                        <i class="fa fa-fw fa-plus mr-5"></i>Nueva recepción
                    </a>
                    <div class="dropdown-divider"></div>
                    @endcan
                    <a class="dropdown-item" href="#" onclick="hRecepcionForanea.reloadTables(['denuncias-datatables','documentos-denuncias-datatables','documentos-datatables'])">
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
    </div>
</div>
@endsection

@push('js-script')
    {{ Html::script('js/helpers/recepcion.foranea.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
    @if (request() -> session() -> has('urlAcuseAutomatico'))
    <script type="text/javascript">
        setTimeout(function(){
            window.open('{{ request() -> session() -> get('urlAcuseAutomatico') }}', '_blank');
        },1000);
    </script>
    @endif

    {{ $table1 -> javascript() }}
    {{ $table2 -> javascript() }}
    {{ $table3 -> javascript() }}
@endpush