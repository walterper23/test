@extends('Tema.app')

@section('title')
    {{ title('Configuración de tipos de documentos') }}
@endsection

@push('css-style')
    {{ Html::style('js/plugins/datatables/dataTables.bootstrap4.min.css') }}
    {{ Html::style('js/plugins/datatables/buttons1.4.2/css/datatables.buttons.bootstrap4.min.css') }}
    {{ Html::style('js/plugins/sweetalert2/sweetalert2.min.css') }}
@endpush

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <a class="breadcrumb-item" href="javascript:void(0)">Sistema</a>
        <span class="breadcrumb-item active">Tipos de documentos</span>
    </nav>
@endsection

@section('content')
    <div class="block block-themed block-mode-loading-refresh">
        <div class="block-header bg-corporate-dark">
            <h3 class="block-title"><i class="fa fa-fw fa-files-o mr-5"></i> Tipos de documentos</h3>
            <div class="block-options">
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
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive font-size-sm">
                {{ $table->html() }}
            </div>
        </div>
    </div>
@stop

@push('js-script')
    {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
    {{ Html::script('js/plugins/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('js/plugins/datatables/dataTables.bootstrap4.min.js') }}
    {{-- Html::script('js/plugins/datatables/buttons1.4.2/js/dataTables.buttons.bootstrap4.min.js') }}
    {{ Html::script('js/plugins/datatables/buttons1.4.2/js/dataTables.buttons.server-side.js') --}}
    {{ Html::script('js/helpers/tipo_documento.helper.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
@endpush

@push('js-custom')
    {!! $table->javascript() !!}
@endpush