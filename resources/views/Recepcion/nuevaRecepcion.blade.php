@extends('Tema.app')

@section('title')
	SIGESD :: Nueva recepción
@endsection

@section('content')
<!-- Block Tabs -->
    <div class="row">
        <div class="col-lg-12">
           <!-- Normal Form -->
            <div class="block block-themed block-bordered">
                <div class="block-header bg-flat-dark">
                    <h3 class="block-title">Nueva recepci&oacute;n</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option">
                            <i class="si si-wrench"></i>
                        </button>
                        <button type="button" class="btn-block-option js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Undo">
                            <i class="si si-action-undo"></i>
                        </button>
                        <button type="button" class="btn-block-option js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Redo">
                            <i class="si si-action-redo"></i>
                        </button>
                        <button type="button" class="btn-block-option js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Edit">
                            <i class="si si-pencil"></i>
                        </button>
                        <div class="dropdown">
                            <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Settings</button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-bell mr-5"></i>News
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-envelope-o mr-5"></i>Messages
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-pencil mr-5"></i>Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content">
                    <form action="be_forms_elements_bootstrap.html" method="post" onsubmit="return false;">
                        <div class="form-group">
                            <label for="example-nf-email">Email</label>
                            <input type="email" class="form-control" id="example-nf-email" name="example-nf-email" placeholder="Enter Email..">
                        </div>
                        <div class="form-group">
                            <label for="example-nf-password">Password</label>
                            <input type="password" class="form-control" id="example-nf-password" name="example-nf-password" placeholder="Enter Password..">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-alt-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Normal Form -->
        </div>
    </div>
    <!-- END Block Tabs -->
@endsection

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