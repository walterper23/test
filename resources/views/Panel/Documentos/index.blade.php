@extends('Tema.app')

@section('title')
	{{ title($title) }}
@endsection

@push('css-style')
    {{ Html::style('js/plugins/datatables/dataTables.bootstrap4.min.css') }}
    {{ Html::style('js/plugins/datatables/buttons1.4.2/css/datatables.buttons.bootstrap4.min.css') }}
    {{ Html::style('js/plugins/sweetalert2/sweetalert2.min.css') }}
@endpush

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-server"></i> Panel de trabajo</a>
        <a class="breadcrumb-item" href="{{ url('panel/documentos/recibidos') }}">Documentos</a>
        <span class="breadcrumb-item active">Recibidos</span>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-5 col-xl-3">
            <!-- Collapsible Inbox Navigation -->
            {!! $conteoDocumentos !!}
            <!-- END Collapsible Inbox Navigation -->

            <!-- Recent Contacts -->
            <div class="block d-none d-md-block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Últimos cambios a documentos</h3>
                    <div class="block-options">
                        <div class="dropdown">
                            <button type="button" class="btn-block-option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-fw fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item active" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-users mr-5"></i> All
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-circle text-success mr-5"></i> Online
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-circle text-danger mr-5"></i> Busy
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-circle text-warning mr-5"></i> Away
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-circle text-muted mr-5"></i> Offline
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-cogs mr-5"></i>Manage
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <ul class="nav-users">
                        <li>
                            <a href="be_pages_generic_profile.html">
                                <img class="img-avatar" src="assets/img/avatars/avatar8.jpg" alt="">
                                <i class="fa fa-circle text-success"></i> Andrea Gardner
                                <div class="font-w400 font-size-xs text-muted"><i class="fa fa-location-arrow"></i> New York</div>
                            </a>
                        </li>
                        <li>
                            <a href="be_pages_generic_profile.html">
                                <img class="img-avatar" src="assets/img/avatars/avatar13.jpg" alt="">
                                <i class="fa fa-circle text-success"></i> Thomas Riley
                                <div class="font-w400 font-size-xs text-muted"><i class="fa fa-location-arrow"></i> San Fransisco</div>
                            </a>
                        </li>
                        <li>
                            <a href="be_pages_generic_profile.html">
                                <img class="img-avatar" src="assets/img/avatars/avatar6.jpg" alt="">
                                <i class="fa fa-circle text-warning"></i> Sara Fields
                                <div class="font-w400 font-size-xs text-muted"><i class="fa fa-location-arrow"></i> Beijing</div>
                            </a>
                        </li>
                        <li>
                            <a href="be_pages_generic_profile.html">
                                <img class="img-avatar" src="assets/img/avatars/avatar11.jpg" alt="">
                                <i class="fa fa-circle text-warning"></i> Wayne Garcia
                                <div class="font-w400 font-size-xs text-muted"><i class="fa fa-location-arrow"></i> Tokyo</div>
                            </a>
                        </li>
                        <li>
                            <a href="be_pages_generic_profile.html">
                                <img class="img-avatar" src="assets/img/avatars/avatar12.jpg" alt="">
                                <i class="fa fa-circle text-danger"></i> Scott Young
                                <div class="font-w400 font-size-xs text-muted"><i class="fa fa-location-arrow"></i> London</div>
                            </a>
                        </li>
                        <li>
                            <a href="be_pages_generic_profile.html">
                                <img class="img-avatar" src="assets/img/avatars/avatar8.jpg" alt="">
                                <i class="fa fa-circle text-danger"></i> Lori Moore
                                <div class="font-w400 font-size-xs text-muted"><i class="fa fa-location-arrow"></i> Rio De Janeiro</div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- END Recent Contacts -->
        </div>
        <div class="col-md-7 col-xl-9">
            <!-- Message List -->
            <div class="block">
                <div class="block-header block-header-default">
                    <div class="block-title">
                        <!--strong>1-10</strong> from <strong>23</strong-->
                    </div>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option">
                            <i class="si si-arrow-left"></i>
                        </button>
                        <button type="button" class="btn-block-option" data-toggle="block-option">
                            <i class="si si-arrow-right"></i>
                        </button>
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    </div>
                </div>
                <div class="block-content">
                    <!-- Messages Options -->
                    <!--div class="push">
                        <button type="button" class="btn btn-rounded btn-alt-secondary float-right">
                            <i class="fa fa-times text-danger mx-5"></i>
                            <span class="d-none d-sm-inline"> Delete</span>
                        </button>
                        <button type="button" class="btn btn-rounded btn-alt-secondary">
                            <i class="fa fa-archive text-primary mx-5"></i>
                            <span class="d-none d-sm-inline"> Archive</span>
                        </button>
                        <button type="button" class="btn btn-rounded btn-alt-secondary">
                            <i class="fa fa-star text-warning mx-5"></i>
                            <span class="d-none d-sm-inline"> Star</span>
                        </button>
                    </div-->
                    <!-- END Messages Options -->

                    <!-- Messages -->
                    <!-- Checkable Table (.js-table-checkable class is initialized in Codebase() -> uiHelperTableToolsCheckable()) -->
                    <table class="js-table-checkable table table-hover table-vcenter">
                        <tbody>
    
                            @foreach( $documentos as $documento )

                            <tr>
                                <td class="d-none d-sm-table-cell font-w600" style="width: 50px;">{{ $documento['doc']->DOCU_NUMERO_FICHA }}</td>
                                <td>
                                    <a class="font-w600" href="{{ url('recepcion/documentos/'.$documento['doc']->DOCU_DOCUMENTO.'/seguimiento') }}">{{ $documento['doc']->DOCU_NUMERO_OFICIO }}</a>
                                    <div class="text-muted mt-5">{{ $documento['doc']->DOCU_DESCRIPCION }}</div>
                                </td>
                                <td>
                                    <div class=" mt-5">{{ $documento['seguimiento']->estadoDocumento->ESDO_NOMBRE }}</div>
                                </td>
                                <td class="text-center" style="width: 120px;">
                                    <a href="{{ url('recepcion/documentos/'.$documento['doc']->DOCU_DOCUMENTO.'/seguimiento') }}" class="btn btn-primary"><i class="fa fa-eye"></i> Ver seguimiento</a>
                                </td>
                                <td class="text-center" style="width: 40px;">
                                    <a href="#" onclick="App.openModal({ url : '{{ url('modal-cambio') }}', id : 'modal-cambio' })" class="btn btn-success"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                    <!-- END Messages -->
                </div>
            </div>
            <!-- END Message List -->
        </div>
    </div>
@endsection

@push('js-script')
    {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
    {{ Html::script('js/plugins/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('js/plugins/datatables/dataTables.bootstrap4.min.js') }}
    {{ Html::script('js/helpers/panel.helper.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
@endpush

@push('js-custom')
	
@endpush