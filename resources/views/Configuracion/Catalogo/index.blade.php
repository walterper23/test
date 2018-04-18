@extends('app.layoutMaster')

@section('title')
	{{ title('Catálogos') }}
@endsection

@push('css-custom')
<style>
    .block-content p {
        margin-bottom : 10px
    }
</style>
@endpush

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <span class="breadcrumb-item active">Cat&aacute;logos</span>
    </nav>
@endsection

@section('content')

    <div class="row gutters-tiny">
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/catalogos/anexos') }}">
                <div class="block-content">
                    <p class="font-size-h1">
                        <strong>{{ $anexos }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-clipboard"></i> Anexos</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/catalogos/direcciones') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-success">
                        <strong>{{ $direcciones }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-sitemap"></i> Direcciones</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/catalogos/departamentos') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-warning">
                        <strong>{{ $departamentos }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-sitemap"></i> Departamentos</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/catalogos/puestos') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-corporate">
                        <strong>{{ $puestos }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-user-secret"></i> Puestos</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/catalogos/estados-documentos') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-danger">
                        <strong>{{ $estadosDocumentos }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-flash"></i> Estados de Documentos</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/usuarios') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-elegance">
                        <strong>{{ $usuarios }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-users"></i> Usuarios</p>
                </div>
            </a>
        </div>
    </div>

@endsection