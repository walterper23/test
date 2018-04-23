@extends('app.layoutMaster')

@section('title')
	{{ title('Seguimiento de documento') }}
@endsection

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="{{ url() -> previous() }}"><i class="fa fa-server"></i> Panel de trabajo</a>
        <a class="breadcrumb-item" href="{{ url() -> previous() }}">Documentos</a>
    </nav>
@endsection

@section('content')
<div class="block">
    <div class="py-30 text-center">
        <div class="display-3 text-success">
            <i class="fa fa-fw fa-paper-plane"></i> Seguimiento no encontrado
        </div>
        <h1 class="h2 font-w700 mt-30 mb-10">Oops.. Lo sentimos</h1>
        <h2 class="h3 font-w400 text-muted mb-10">El seguimiento solicitado no es válido.</h2>
        <h4 class="h5 font-w400 text-muted mb-50">Intente de nuevo o contacte al Administrador del Sistema.</h4>
        <a class="btn btn-hero btn-rounded btn-alt-secondary" href="{{ url()->previous() }}">
            <i class="fa fa-arrow-left mr-10"></i> Regresar
        </a>
    </div>
</div>
@endsection