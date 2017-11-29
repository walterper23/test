@extends('Tema.app')

@section('title')
	SIGESD :: Seguimiento de documento
@endsection

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-home"></i> Recepci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('recepcion/documentos') }}">Documentos</a>
        <a class="breadcrumb-item" href="{{ url('recepcion/documentos') }}">Ver seguimiento</a>
        <span class="breadcrumb-item active">{{ $documento->DOCU_NUMERO_OFICIO }}</span>
    </nav>
@endsection

@section('content')
	<!-- Timeline Activity -->
    <h2 class="content-heading">{{ $documento->DOCU_NUMERO_OFICIO }} <small>Seguimiento del documento</small></h2>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Eventos</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content">
            <ul class="list list-activity">
                <li>
                    <i class="si si-wallet text-success"></i>
                    <div class="font-w600">+$29 New sale</div>
                    <div>
                        <a href="javascript:void(0)">Admin Template</a>
                    </div>
                    <div class="font-size-xs text-muted">5 min ago</div>
                </li>
                <li>
                    <i class="si si-close text-danger"></i>
                    <div class="font-w600">Project removed</div>
                    <div>
                        <a href="javascript:void(0)">Best Icon Set</a>
                    </div>
                    <div class="font-size-xs text-muted">26 min ago</div>
                </li>
                <li>
                    <i class="si si-pencil text-info"></i>
                    <div class="font-w600">You edited the file</div>
                    <div>
                        <a href="javascript:void(0)">
                            <i class="fa fa-file-text-o"></i> Docs.doc
                        </a>
                    </div>
                    <div class="font-size-xs text-muted">3 hours ago</div>
                </li>
                <li>
                    <i class="si si-plus text-success"></i>
                    <div class="font-w600">New user</div>
                    <div>
                        <a href="javascript:void(0)">StudioWeb - View Profile</a>
                    </div>
                    <div class="font-size-xs text-muted">5 hours ago</div>
                </li>
                <li>
                    <i class="si si-wrench text-warning"></i>
                    <div class="font-w600">Core v3.9 is available</div>
                    <div>
                        <a href="javascript:void(0)">Update now</a>
                    </div>
                    <div class="font-size-xs text-muted">8 hours ago</div>
                </li>
                <li>
                    <i class="si si-user-follow text-pulse"></i>
                    <div class="font-w600">+1 Friend Request</div>
                    <div>
                        <a href="javascript:void(0)">Accept</a>
                    </div>
                    <div class="font-size-xs text-muted">1 day ago</div>
                </li>
            </ul>
        </div>
    </div>
    <!-- END Timeline Activity -->
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