@extends('Tema.app')

@section('title')
	SIGESD :: Catálogos
@endsection

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <span class="breadcrumb-item active">Cat&aacute;logos</span>
    </nav>
@endsection

@section('content')
	<div class="row gutters-tiny">
        <div class="col-md-3">
            <a class="block block-rounded block-link-shadow" href="{{ url('configuracion/catalogos/anexos') }}">
                <div class="block-content block-content-full block-sticky-options">
                    <div class="block-options">
                        <div class="block-options-item">
                            <i class="fa fa-clipboard fa-2x text-info-light"></i>
                        </div>
                    </div>
                    <div class="py-20 text-center">
                        <div class="font-size-h2 font-w700 mb-0 text-info js-count-to-enabled" data-toggle="countTo" data-to="{{ $anexos }}">{{ $anexos }}</div>
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Anexos</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a class="block block-rounded block-link-shadow" href="{{ url('configuracion/catalogos/departamentos') }}">
                <div class="block-content block-content-full block-sticky-options">
                    <div class="block-options">
                        <div class="block-options-item">
                            <i class="fa fa-sitemap fa-2x text-danger-light"></i>
                        </div>
                    </div>
                    <div class="py-20 text-center">
                        <div class="font-size-h2 font-w700 mb-0 text-danger js-count-to-enabled" data-toggle="countTo" data-to="{{ $departamentos }}">{{ $departamentos }}</div>
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Departamentos</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a class="block block-rounded block-link-shadow" href="{{ url('configuracion/catalogos/direcciones') }}">
                <div class="block-content block-content-full block-sticky-options">
                    <div class="block-options">
                        <div class="block-options-item">
                            <i class="fa fa-sitemap fa-2x text-danger-light"></i>
                        </div>
                    </div>
                    <div class="py-20 text-center">
                        <div class="font-size-h2 font-w700 mb-0 text-danger js-count-to-enabled" data-toggle="countTo" data-to="{{ $direcciones }}">{{ $direcciones }}</div>
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Direcciones</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a class="block block-rounded block-link-shadow" href="{{ url('configuracion/catalogos/puestos') }}">
                <div class="block-content block-content-full block-sticky-options">
                    <div class="block-options">
                        <div class="block-options-item">
                            <i class="fa fa-users fa-2x text-success-light"></i>
                        </div>
                    </div>
                    <div class="py-20 text-center">
                        <div class="font-size-h2 font-w700 mb-0 text-success js-count-to-enabled" data-toggle="countTo" data-to="{{ $puestos}}">{{ $puestos}}</div>
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Puestos</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection