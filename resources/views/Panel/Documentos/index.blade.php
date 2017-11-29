@extends('Tema.app')

@section('title')
	SIGESD :: Documentos recibidos
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
            <div class="js-inbox-nav d-none d-md-block">
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Menú</h3>
                        <div class="block-options">
                            <div class="dropdown">
                                <button type="button" class="btn-block-option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-fw fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa fa-fw fa-flask mr-5"></i>Filter
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa fa-fw fa-cogs mr-5"></i>Manage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block-content">
                        <ul class="nav nav-pills flex-column push">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center justify-content-between active" href="{{ url('panel/documentos/?type=received') }}">
                                    <span><i class="fa fa-fw fa-leanpub mr-5"></i> Recibidos</span>
                                    <span class="badge badge-pill badge-secondary">23</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center justify-content-between" href="{{ url('panel/documentos/?type=all') }}">
                                    <span><i class="fa fa-fw fa-send mr-5"></i> Todos</span>
                                    <span class="badge badge-pill badge-secondary">79</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center justify-content-between" href="{{ url('panel/documentos/?type=important') }}">
                                    <span><i class="fa fa-fw fa-star mr-5"></i> Importantes</span>
                                    <span class="badge badge-pill badge-secondary">30</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center justify-content-between" href="{{ url('panel/documentos/?type=finished') }}">
                                    <span><i class="fa fa-fw fa-flag-checkered mr-5"></i> Finalizados</span>
                                    <span class="badge badge-pill badge-secondary">10</span>
                                </a>
                            </li>
                            <!--li class="nav-item">
                                <hr class="my-5">
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-plus mr-5"></i> Create label
                                </a>
                            </li-->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- END Collapsible Inbox Navigation -->

            <!-- Recent Contacts -->
            <div class="block d-none d-md-block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Recent Contacts</h3>
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
                        <strong>1-10</strong> from <strong>23</strong>
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
                    <div class="push">
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
                    </div>
                    <!-- END Messages Options -->

                    <!-- Messages -->
                    <!-- Checkable Table (.js-table-checkable class is initialized in Codebase() -> uiHelperTableToolsCheckable()) -->
                    <table class="js-table-checkable table table-hover table-vcenter">
                        <tbody>
                            <tr>
                                <td class="text-center" style="width: 40px;">
                                    <label class="css-control css-control-primary css-checkbox">
                                        <input type="checkbox" class="css-control-input">
                                        <span class="css-control-indicator"></span>
                                    </label>
                                </td>
                                <td class="d-none d-sm-table-cell font-w600" style="width: 140px;">Wayne Garcia</td>
                                <td>
                                    <a class="font-w600" data-toggle="modal" data-target="#modal-message" href="#">Welcome to our service</a>
                                    <div class="text-muted mt-5">It's a pleasure to have you on our service..</div>
                                </td>
                                <td class="d-none d-xl-table-cell font-w600 font-size-sm text-muted" style="width: 120px;">WED</td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label class="css-control css-control-primary css-checkbox">
                                        <input type="checkbox" class="css-control-input">
                                        <span class="css-control-indicator"></span>
                                    </label>
                                </td>
                                <td class="d-none d-sm-table-cell font-w600">Jose Wagner</td>
                                <td>
                                    <a class="font-w600" data-toggle="modal" data-target="#modal-message" href="#">Your subscription was updated</a>
                                    <div class="text-muted mt-5">We are glad you decided to go with a vip..</div>
                                </td>
                                <td class="d-none d-xl-table-cell font-w600 font-size-sm text-muted">WED</td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label class="css-control css-control-primary css-checkbox">
                                        <input type="checkbox" class="css-control-input">
                                        <span class="css-control-indicator"></span>
                                    </label>
                                </td>
                                <td class="d-none d-sm-table-cell font-w600">Alice Moore</td>
                                <td>
                                    <a class="font-w600" data-toggle="modal" data-target="#modal-message" href="#">Update is available</a>
                                    <div class="text-muted mt-5">An update is under way for your app..</div>
                                </td>
                                <td class="d-none d-xl-table-cell font-w600 font-size-sm text-muted">FRI</td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label class="css-control css-control-primary css-checkbox">
                                        <input type="checkbox" class="css-control-input">
                                        <span class="css-control-indicator"></span>
                                    </label>
                                </td>
                                <td class="d-none d-sm-table-cell font-w600">Brian Cruz</td>
                                <td>
                                    <a class="font-w600" data-toggle="modal" data-target="#modal-message" href="#">New Sale!</a>
                                    <div class="text-muted mt-5">You had a new sale and earned..</div>
                                </td>
                                <td class="d-none d-xl-table-cell font-w600 font-size-sm text-muted">THU</td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label class="css-control css-control-primary css-checkbox">
                                        <input type="checkbox" class="css-control-input">
                                        <span class="css-control-indicator"></span>
                                    </label>
                                </td>
                                <td class="d-none d-sm-table-cell font-w600">Marie Duncan</td>
                                <td>
                                    <a class="font-w600" data-toggle="modal" data-target="#modal-message" href="#">Action Required for your account!</a>
                                    <div class="text-muted mt-5">Your account is inactive for a long time and..</div>
                                </td>
                                <td class="d-none d-xl-table-cell font-w600 font-size-sm text-muted">MON</td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label class="css-control css-control-primary css-checkbox">
                                        <input type="checkbox" class="css-control-input">
                                        <span class="css-control-indicator"></span>
                                    </label>
                                </td>
                                <td class="d-none d-sm-table-cell font-w600">Albert Ray</td>
                                <td>
                                    <a class="font-w600" data-toggle="modal" data-target="#modal-message" href="#">New Photo Pack!</a>
                                    <div class="text-muted mt-5">Our new photo pack is available now! You..</div>
                                </td>
                                <td class="d-none d-xl-table-cell font-w600 font-size-sm text-muted">MON</td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label class="css-control css-control-primary css-checkbox">
                                        <input type="checkbox" class="css-control-input">
                                        <span class="css-control-indicator"></span>
                                    </label>
                                </td>
                                <td class="d-none d-sm-table-cell font-w600">Lisa Jenkins</td>
                                <td>
                                    <a class="font-w600" data-toggle="modal" data-target="#modal-message" href="#">Product is released!</a>
                                    <div class="text-muted mt-5">This is a notification about our new product..</div>
                                </td>
                                <td class="d-none d-xl-table-cell font-w600 font-size-sm text-muted">TUE</td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label class="css-control css-control-primary css-checkbox">
                                        <input type="checkbox" class="css-control-input">
                                        <span class="css-control-indicator"></span>
                                    </label>
                                </td>
                                <td class="d-none d-sm-table-cell font-w600">Wayne Garcia</td>
                                <td>
                                    <a class="font-w600" data-toggle="modal" data-target="#modal-message" href="#">Now on Sale!</a>
                                    <div class="text-muted mt-5">Our Book is out! You can buy a copy and..</div>
                                </td>
                                <td class="d-none d-xl-table-cell font-w600 font-size-sm text-muted">WED</td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label class="css-control css-control-primary css-checkbox">
                                        <input type="checkbox" class="css-control-input">
                                        <span class="css-control-indicator"></span>
                                    </label>
                                </td>
                                <td class="d-none d-sm-table-cell font-w600">Helen Jacobs</td>
                                <td>
                                    <a class="font-w600" data-toggle="modal" data-target="#modal-message" href="#">Monthly Report</a>
                                    <div class="text-muted mt-5">The monthly report you requested for..</div>
                                </td>
                                <td class="d-none d-xl-table-cell font-w600 font-size-sm text-muted">SAT</td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label class="css-control css-control-primary css-checkbox">
                                        <input type="checkbox" class="css-control-input">
                                        <span class="css-control-indicator"></span>
                                    </label>
                                </td>
                                <td class="d-none d-sm-table-cell font-w600">Jose Mills</td>
                                <td>
                                    <a class="font-w600" data-toggle="modal" data-target="#modal-message" href="#">Trial Started!</a>
                                    <div class="text-muted mt-5">You 30-day trial has now started and..</div>
                                </td>
                                <td class="d-none d-xl-table-cell font-w600 font-size-sm text-muted">SUN</td>
                            </tr>
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