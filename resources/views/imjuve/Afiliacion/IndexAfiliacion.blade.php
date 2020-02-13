@extends('app.layoutMaster')

@section('title', title('Afiliaciones :: Lista') )

@include('vendor.plugins.datatables')
@include('vendor.plugins.datepicker')
@include('vendor.plugins.select2')

@section('breadcrumb')
	<nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <span class="breadcrumb-item active">Afiliados</span>
    </nav>
@endsection

@section('content')
	<div class="block block-themed block-mode-loading-refresh">
        <div class="block-header bg-default">
            <h3 class="block-title"><i class="fa fa-fw fa-users mr-5"></i> Afiliados</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option d-none d-sm-inline" onclick="hUsuario.new_('{{$form_id}}','{{$form_url}}')">
                    <i class="fa fa-user-plus"></i> Nueva afiliación
                </button>
                <button type="button" class="btn-block-option d-none d-sm-inline" onclick="hUsuario.reload('dataTableBuilder')">
                    <i class="fa fa-refresh"></i> Actualizar
                </button>
                <div class="dropdown">
                    <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Opciones</button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="javascript:void(0)" onclick="hUsuario.new_('{{$form_id}}','{{$form_url}}')">
                            <i class="fa fa-fw fa-user-plus mr-5"></i>Nueva afiliación
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
    {{ Html::script('js/helpers/usuario.helper.js') }}
    {{ Html::script('js/helpers/imjuve/afiliado.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
    {!! $table->javascript() !!}
@endpush