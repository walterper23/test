@extends('app.layoutMaster')

@section('title', title('Configuración de estados de documentos') )

@include('vendor.plugins.datatables')

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <a class="breadcrumb-item" href="javascript:void(0)">Sistema</a>
        <span class="breadcrumb-item active">Estados de documentos</span>
    </nav>
@endsection

@section('content')
    <div class="block block-themed block-mode-loading-refresh">
        <div class="block-header bg-primary">
            <h3 class="block-title"><i class="fa fa-fw fa-tags mr-5"></i> Estados de documentos</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive font-size-sm">
                {{ $table->html() }}
            </div>
        </div>
    </div>
@stop

@push('js-script')
    {{ Html::script('js/helpers/sistema_estado_documento.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
    {!! $table->javascript() !!}
@endpush