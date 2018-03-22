@extends('Tema.app')

@section('title')
	{{ title('Configuración de permisos de usuarios') }}
@endsection

@section('breadcrumb')
	<nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('configuracion/usuarios') }}">Usuarios</a>
        <span class="breadcrumb-item active">Permisos</span>
    </nav>
@endsection

@section('content')

@endsection