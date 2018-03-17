@extends('Tema.app')

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
            <a class="nav-link active" href="#btabswo-static-one">Denuncias</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#btabswo-static-two">Doctos. denuncias</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#btabswo-static-three">Documentos</a>
        </li>
        <li class="nav-item ml-auto">
            <div class="block-options mr-15">
            <a href="{{ url('recepcion/documentos/nueva-recepcion') }}" class="btn-block-option">
                <i class="fa fa-plus"></i> Nueva recepci&oacute;n
            </a>
            <button type="button" class="btn-block-option" onclick="hRecepcion.reload('dataTableBuilder')">
                <i class="fa fa-refresh"></i> Actualizar
            </button>
            <div class="dropdown">
                <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Opciones</button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="javascript:void(0)">
                        <i class="fa fa-fw fa-bell mr-5"></i>News
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="javascript:void(0)">
                        <i class="fa fa-fw fa-pencil mr-5"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
        </li>
    </ul>
    <div class="block-content tab-content">
        <div class="tab-pane active" id="btabswo-static-home" role="tabpanel">
            <div class="table-responsive">
                {{ $table1->html() }}
            </div>
        </div>
        <div class="tab-pane" id="btabswo-static-profile" role="tabpanel">
            <h4 class="font-w400">Profile Content</h4>
            <p>...</p>
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
    {{ $table1->javascript() }}
@endpush