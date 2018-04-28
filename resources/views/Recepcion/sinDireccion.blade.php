
@extends('app.layoutMaster')

@section('title')
	{{ title('Nueva recepción de documento foráneo') }}
@endsection

@push('css-style')
    {{ Html::style('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}
    {{ Html::style('js/plugins/select2/select2.min.css') }}
    {{ Html::style('js/plugins/select2/select2-bootstrap.min.css') }}
@endpush

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-home"></i> Recepci&oacute;n foránea</a>
        <a class="breadcrumb-item" href="{{ url('recepcion/documentos') }}">Documentos</a>
        <span class="breadcrumb-item active">Nueva recepci&oacute;n</span>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="block">
                <div class="py-30 text-center">
                    <div class="display-3 text-corporate">
                        <i class="fa fa-sitemap"></i> Sin dirección
                    </div>
                    <h1 class="h2 font-w700 mt-30 mb-10">Oops.. Lo sentimos</h1>
                    <h2 class="h3 font-w400 text-muted mb-10">Pero necesitas tener asignada al menos una Dirección de la Dependencia para continuar.</h2>
                    <h4 class="h5 font-w400 text-muted mb-50">Contacte al Administrador del Sistema.</h4>
                    <a class="btn btn-hero btn-rounded btn-alt-secondary" href="{{ url()->previous() }}">
                        <i class="fa fa-arrow-left mr-10"></i> Regresar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection