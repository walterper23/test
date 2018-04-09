@extends('app.layoutMaster')

@section('title')
	{{ title('Configuración de permisos y asignaciones de usuarios') }}
@endsection

@section('breadcrumb')
	<nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('configuracion/usuarios') }}">Usuarios</a>
        <span class="breadcrumb-item active">Permisos y Asignaciones</span>
    </nav>
@endsection

@section('content')
	<div class="block block-themed block-mode-loading-refresh">
	    <ul class="nav nav-tabs nav-tabs-alt nav-tabs-block align-items-center" data-toggle="tabs" role="tablist">
	        <li class="nav-item">
	            <a class="nav-link active" href="#btabswo-static-one">Permisos</a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" href="#btabswo-static-two">Asignaciones</a>
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
	        <div class="tab-pane active" id="btabswo-static-one" role="tabpanel">
	            <div class="table-responsive">
	                
	            </div>
	        </div>
	        <div class="tab-pane" id="btabswo-static-two" role="tabpanel">
	            <div class="table-responsive">
	                
	            </div>
	        </div>
	    </div>
	</div>
@endsection

@push('js-script')

@endpush