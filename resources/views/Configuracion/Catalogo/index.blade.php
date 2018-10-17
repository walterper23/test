@extends('app.layoutMaster')

@section('title', title('Catálogos') )

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

    <h2 class="content-heading text-center" style="border-bottom: 0px;">Catálogos <small>del sistema</small></h2>

    <div class="row gutters-tiny justify-content-center">
        @if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.DIRECC') )
        <div class="col-6 col-md-4 col-xl-3">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/catalogos/direcciones') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-success">
                        <strong>{{ $direcciones }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-legal"></i> Direcciones</p>
                </div>
            </a>
        </div>
        @endif
        @if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.DEPTOS') )
        <div class="col-6 col-md-4 col-xl-3">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/catalogos/departamentos') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-warning">
                        <strong>{{ $departamentos }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-sitemap"></i> Departamentos</p>
                </div>
            </a>
        </div>
        @endif
        @if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.PUESTOS') )
        <div class="col-6 col-md-4 col-xl-3">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/catalogos/puestos') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-corporate">
                        <strong>{{ $puestos }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-user-secret"></i> Puestos</p>
                </div>
            </a>
        </div>
        @endif
    </div>
    <div class="row gutters-tiny justify-content-center">
        @if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.ANEXOS') )
        <div class="col-6 col-md-4 col-xl-3">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/catalogos/anexos') }}">
                <div class="block-content">
                    <p class="font-size-h1">
                        <strong>{{ $anexos }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-clipboard"></i> Anexos</p>
                </div>
            </a>
        </div>
        @endif
        @if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.ESTA.DOC') )
        <div class="col-6 col-md-4 col-xl-3">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/catalogos/estados-documentos') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-danger">
                        <strong>{{ $estadosDocumentos }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-flash"></i> Estados de Documentos</p>
                </div>
            </a>
        </div>
        @endif
        @if( user()->canAtLeast('USU.ADMIN.USUARIOS') )
        <div class="col-6 col-md-4 col-xl-3">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/usuarios') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-elegance">
                        <strong>{{ $usuarios }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-users"></i> Usuarios</p>
                </div>
            </a>
        </div>
        @endif
    </div>

    <h2 class="content-heading text-center mt-10" style="border-bottom: 0px;">Configuraciones <small>del sistema</small></h2>

    <div class="row gutters-tiny justify-content-center">
        @if( user()->canAtLeast('SIS.ADMIN.CONFIG') )
        <div class="col-6 col-md-4 col-xl-3">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/sistema/tipos-documentos') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-primary">
                        <strong>{{ $systemTiposDocumentos }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-files-o"></i> Tipos de documentos</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/sistema/estados-documentos') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-primary">
                        <strong>{{ $systemEstadosDocumentos }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-tags"></i> Estados de documentos</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <a class="block block-rounded block-bordered block-link-pop text-center" href="{{ url('configuracion/sistema/variables') }}">
                <div class="block-content">
                    <p class="font-size-h1 text-pulse">
                        <strong>{{ $systemConfigVariables }}</strong>
                    </p>
                    <p class="font-w600"><i class="fa fa-fw fa-cogs"></i> Variables</p>
                </div>
            </a>
        </div>
        @endif
    </div>
@endsection