@extends('app.layoutMaster')

@section('title')
    {{ title('Bitácora') }}
@endsection

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('configuracion/usuarios') }}">Sistema</a>
        <span class="breadcrumb-item active">Bitácora</span>
    </nav>
@endsection

@section('content')

@endsection