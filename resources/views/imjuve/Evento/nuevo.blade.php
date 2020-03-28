@extends('app.layoutMaster')

@section('title', title('Afiliaciones :: Lista') )

@include('vendor.plugins.datatables')
@include('vendor.plugins.datepicker')
@include('vendor.plugins.select2')

@section('breadcrumb')
	<nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <span class="breadcrumb-item active">Afiliados</span>
    </nav>
@endsection

@section('content')
  <div class="main-panel">
    <div class="content">
      <div class="page-inner">
        <div class="page-header">
          <h4 class="page-title">Eventos</h4>
          
        </div>
          <div class="col-md-12">
            <div class="card">
              
                @include('imjuve.Evento.fragments.form')
              
            </div>
          </div>
      </div>
    </div>
    @endsection

@push('js-script')
    {{ Html::script('js/helpers/usuario.helper.js') }}
    {{ Html::script('js/helpers/imjuve/afiliado.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush


