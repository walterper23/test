@extends('Tema.app')

@section('title')
    SIGESD :: Cat&aacute;logos - Tipos de documentos
@stop

@push('css-style')
    {{ Html::style('js/plugins/datatables/dataTables.bootstrap4.min.css') }}
    {{ Html::style('js/plugins/datatables/buttons1.4.2/css/datatables.buttons.bootstrap4.min.css') }}
    {{ Html::style('js/plugins/sweetalert2/sweetalert2.min.css') }}
@endpush

@section('content')
    <!-- Block Tabs -->
    <div class="row">
        <div class="col-lg-12">
            <!-- Block Tabs Alternative Style -->
            <div class="block">
                <div class="block-content block-content-full">
                   @include('Configuracion.Catalogo.navbarItems')
                    <div class="col-12">
                        <button type="button" class="btn btn-alt-primary" onclick="App.openModal({ title : 'Ricardo', url : '/configuracion/catalogos/tipos-documentos/nuevo', id : 'form-nuevo-tipo-documento' })"><i class="fa fa-plus"></i> Nuevo</button>
	                    <!--button type="button" class="btn btn-alt-primary" id="importbutton"><i class="fa fa-plus"></i> Checar</button-->
                    </div>
                    <div class="table-responsive">
                        {!! $table->html() !!}
                    </div>
               	</div>

            </div>
            <!-- END Block Tabs Alternative Style -->
        </div>
    </div>
    <!-- END Block Tabs -->
@stop

@push('js-script')
    {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
    {{ Html::script('js/plugins/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('js/plugins/datatables/dataTables.bootstrap4.min.js') }}
    {{ Html::script('js/plugins/datatables/buttons1.4.2/js/dataTables.buttons.bootstrap4.min.js') }}
    {{ Html::script('js/plugins/datatables/buttons1.4.2/js/dataTables.buttons.server-side.js') }}
    {{ Html::script('js/plugins/sweetalert2/sweetalert2.min.js') }}
    {{ Html::script('js/helpers/tipo_documento.helper.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
@endpush

@push('js-custom')
    {!! $table->javascript() !!}
@endpush