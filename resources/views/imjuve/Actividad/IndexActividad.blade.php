@extends('app.layoutMaster')

@section('title', title('Configuración de actividades') )

@include('vendor.plugins.datatables')

@section('breadcrumb')
	<nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <span class="breadcrumb-item active">Actividades</span>
    </nav>
@endsection

@section('content')
	<div class="block block-themed block-mode-loading-refresh">
        <div class="block-header bg-earth">
            <h3 class="block-title"><i class="fa fa-book fa-fw mr-5"></i> Actividades</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option d-none d-sm-inline" onclick="hUsuario.new_('{{$form_id}}','{{$form_url}}')">
                    <i class="fa fa-book fa-fw"></i> Nueva actividad
                </button>
                <button type="button" class="btn-block-option d-none d-sm-inline" onclick="hUsuario.reload('dataTableBuilder')">
                    <i class="fa fa-refresh"></i> Actualizar
                </button>
                <div class="dropdown">
                    <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Opciones</button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="javascript:void(0)" onclick="hUsuario.new_('{{$form_id}}','{{$form_url}}')">
                            <i class="fa fa-book fa-fw"></i>Nueva actividad
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="hUsuario.reload('dataTableBuilder')">
                            <i class="fa fa-fw fa-refresh mr-5"></i>Actualizar registros
                        </a>
                        <!--a class="dropdown-item" href="{{ url('configuracion/sistema/bitacora/accesos') }}">
                            <i class="fa fa-fw fa-list-ul mr-5"></i>Bitácora de accesos
                        </a-->
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive font-size-sm">
                {{ $table->html()  }}
            </div>
        </div>
   	</div>
@endsection

@push('js-script')
{{ Html::script('js/helpers/imjuve/actividad.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
    {!! $table->javascript() !!}
@endpush

