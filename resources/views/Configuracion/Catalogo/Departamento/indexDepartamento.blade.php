@extends('app.layoutMaster')

@section('title', title('Configuración de Departamentos') )

@include('vendor.plugins.datatables')

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('configuracion/catalogos') }}">Cat&aacute;logos</a>
        <span class="breadcrumb-item active">Departamentos</span>
    </nav>
@endsection

@section('content')
    <div class="block block-themed block-mode-loading-refresh">
        <div class="block-header bg-corporate-darker">
            <h3 class="block-title"><i class="fa fa-fw fa-sitemap mr-5"></i> Departamentos</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" onclick="hDepartamento.new('{{ $form_id}}', '{{$form_url}}')">
                    <i class="fa fa-plus"></i> Nuevo
                </button>
                <button type="button" class="btn-block-option" onclick="hDepartamento.reload('dataTableBuilder')">
                    <i class="fa fa-refresh"></i> Actualizar
                </button>
                <div class="dropdown">
                    <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Opciones</button>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if(user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.DIRECC'))
                        <a class="dropdown-item" href="{{ url('configuracion/catalogos/direcciones') }}">
                            <i class="fa fa-fw fa-sitemap mr-5"></i>Direcciones
                        </a>
                        @endif
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
@endsection

@push('js-script')
    {{ Html::script('js/helpers/departamento.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
    {!! $table->javascript() !!}
@endpush