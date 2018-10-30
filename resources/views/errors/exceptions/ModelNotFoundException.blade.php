@extends('app.layoutMaster')

@section('title', title('Recurso no disponible') )

@section('content')
	<div class="row">
        <div class="col-lg-12">
            <div class="block">
                <div class="py-30 text-center">
                    <div class="display-3 text-corporate">
                        <i class="fa fa-sitemap"></i> Recurso no disponible
                    </div>
                    <h1 class="h2 font-w700 mt-30 mb-10">Oops.. Lo sentimos</h1>
                    <h2 class="h3 font-w400 text-muted mb-10">Pero el recurso solicitado no está disponible.</h2>
                    <h4 class="h5 font-w400 text-muted mb-50">Contacte al Administrador del Sistema.</h4>
                    <a class="btn btn-hero btn-rounded btn-alt-secondary" href="{{ url()->previous() }}">
                        <i class="fa fa-arrow-left mr-10"></i> Regresar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection