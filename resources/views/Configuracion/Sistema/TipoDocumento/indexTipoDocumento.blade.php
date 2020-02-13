@extends('app.layoutMaster')

@section('title', title('Configuración de tipos de documentos') )

@include('vendor.plugins.datatables')

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <a class="breadcrumb-item" href="javascript:void(0)">Sistema</a>
        <span class="breadcrumb-item active">Tipos de documentos</span>
    </nav>
@endsection

@section('content')
    <div class="block block-themed block-mode-loading-refresh">
        <div class="block-header bg-primary">
            <h3 class="block-title"><i class="fa fa-fw fa-files-o mr-5"></i> Tipos de documentos</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option d-none d-sm-inline" onclick="hSistemaTipoDocumento.new('{{$form_id}}','{{$form_url}}')">
                    <i class="fa fa-plus"></i> Nuevo
                </button>
                <button type="button" class="btn-block-option d-none d-sm-inline" onclick="hSistemaTipoDocumento.reload('dataTableBuilder')">
                    <i class="fa fa-refresh"></i> Actualizar
                </button>
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
    {{ Html::script('js/helpers/sistema_tipo_documento.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
    {!! $table->javascript() !!}
@endpush