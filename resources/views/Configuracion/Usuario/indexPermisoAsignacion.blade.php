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

@endsection

@push('js-script')

@endpush