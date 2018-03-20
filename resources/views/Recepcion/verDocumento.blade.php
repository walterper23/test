@extends('Tema.app')

@section('title')
    {{ title('Ver documento') }}
@endsection

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-home"></i> Recepci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('recepcion/documentos') }}">Documentos</a>
        <a class="breadcrumb-item" href="{{ url('recepcion/documentos') }}">Recepcionados</a>
        <span class="breadcrumb-item active">{{ $documento->DOCU_NUMERO_OFICIO }}</span>
    </nav>
@endsection

@section('content')
	<div class="row">
        <div class="col-lg-12">
           <!-- Normal Form -->
            <div class="block block-themed">
                <div class="block-header bg-corporate">
                    <h3 class="block-title">Documento # {{ $documento->DOCU_NUMERO_FICHA . ' - ' . $documento->DOCU_NUMERO_OFICIO }}</h3>
                    <div class="block-options">
                    	<a class="btn-block-option" href="{{ url('recepcion/documentos/'.$documento->DOCU_DOCUMENTO.'/seguimiento') }}">
		                    <i class="fa fa-eye"></i> Seguimiento
		                </a>
                        <div class="dropdown">
                            <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Opciones</button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-pencil mr-5"></i>Editar
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" onclick="hRecepcion.eliminar()">
                                    <i class="fa fa-fw fa-times mr-5"></i>Eliminar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content">
                    <h5>Información del documento recepcionado</h5>
                </div>
            </div>
            <!-- END Normal Form -->
        </div>
    </div>
@endsection

@push('js-script')
    {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
    {{ Html::script('js/helpers/recepcion.helper.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">



</script>
@endpush