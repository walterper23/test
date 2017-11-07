@extends('Tema.app')

@section('title')
    SIGESD :: Cat&aacute;logos - Tipos de documentos
@stop

@push('css-style')
    {{ Html::style('js/plugins/datatables/dataTables.bootstrap4.min.css') }}
    {{ Html::style('js/plugins/sweetalert2/sweetalert2.min.css') }}
@endpush

@section('content')
    <!-- Block Tabs -->
    <div class="row">
        <div class="col-lg-12">
            <!-- Block Tabs Alternative Style -->
            <div class="block">
                <div class="block-content block-content-full">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ url('configuracion/catalogos/direcciones') }}">
                                <i class="fa fa-fw fa-files-o mr-5"></i> Direcciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('configuracion/catalogos/departamentos') }}">Departamentos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('configuracion/catalogos/tipos-documentos') }}">Tipos de documentos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('configuracion/catalogos/anexos') }}">Anexos</a>
                        </li>
                    </ul>
                    <div class="col-12">
	                    <button type="button" class="btn btn-alt-primary" onclick="App.openModal({ title : 'Ricardo', url : '/configuracion/catalogos/tipos-documentos/nuevo', id : 'form-nuevo-tipo-documento' })"><i class="fa fa-plus"></i> Nuevo</button>
                    </div>
                    <div class="table-responsive">
                        {!! $table->render() !!}
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
    {{ Html::script('js/plugins/sweetalert2/sweetalert2.min.js') }}
    {{ Html::script('js/helpers/tipo_documento.helper.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
@endpush

@push('js-custom')
    {!! $table->script() !!}
@endpush