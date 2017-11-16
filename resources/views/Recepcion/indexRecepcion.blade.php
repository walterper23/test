@extends('Tema.app')

@section('title')
	SIGESD :: Documentos recepcionados
@endsection

@push('css-style')
    {{ Html::style('js/plugins/datatables/dataTables.bootstrap4.min.css') }}
    {{ Html::style('js/plugins/datatables/buttons1.4.2/css/datatables.buttons.bootstrap4.min.css') }}
    {{ Html::style('js/plugins/sweetalert2/sweetalert2.min.css') }}
@endpush

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-home"></i> Recepci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('recepcion/documentos') }}">Documentos</a>
        <span class="breadcrumb-item active">Recepcionados</span>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header bg-corporate-dark">
                    <h3 class="block-title">
                        Documentos
                    </h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="col-12">
                        <button type="button" class="btn btn-alt-primary" onclick="App.openModal({url : '/configuracion/catalogos/anexos/nuevo', id : 'form-nuevo-formDepartamento' })"><i class="fa fa-plus"></i> Nuevo</button>
	                    <!--button type="button" class="btn btn-alt-primary" id="importbutton"><i class="fa fa-plus"></i> Checar</button-->
                    </div>                  
                    <div class="table-responsive">
                    	{{$table->html()}}
                    </div>
               	</div>
            </div>
            <!-- END Block Tabs Alternative Style -->
        </div>
    </div>
    <!-- END Block Tabs -->
@endsection

@push('js-script')
    {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
    {{ Html::script('js/plugins/sweetalert2/sweetalert2.min.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
@endpush

@push('js-custom')
	{!! $table->javascript()!!}
@endpush