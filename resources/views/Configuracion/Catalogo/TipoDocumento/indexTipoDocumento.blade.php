@extends('Tema.app')

@section('title')
    SIGESD :: Cat&aacute;logos - Tipos de documentos
@stop

@push('css-style')
    {{ Html::style('js/plugins/datatables/dataTables.bootstrap4.min.css') }}
@endpush

@section('content')
    <!-- Block Tabs -->
    <div class="row">
        <div class="col-lg-12">
            <!-- Block Tabs Alternative Style -->
            <div class="block">
                <ul class="nav nav-tabs nav-tabs-alt align-items-center" data-toggle="tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#btabs-tipos-documentos">Tipos documentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#btabs-anexos">Anexos</a>
                    </li>
                    <li class="nav-item ml-auto">
                        <div class="block-options mr-15">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                        </div>
                    </li>
                </ul>
                <div class="block-content tab-content">
                    <div class="tab-pane active" id="btabs-tipos-documentos" role="tabpanel">
                    	<div class="block">
                            <div class="block-content block-content-full">
			                    <div class="col-12">
				                    <button type="button" class="btn btn-alt-primary" onclick="App.openModal({ title : 'Ricardo', url : '/configuracion/catalogos/tipos-documentos/nuevo', id : 'form-nuevo-tipo-documento' })"><i class="fa fa-plus"></i> Nuevo</button>
			                    </div>
		                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/be_tables_datatables.js -->
		                        <table class="table table-bordered table-striped table-vcenter table-sm datatable-tipos-documentos">
		                            <thead class="thead-default">
		                                <tr>
		                                    <th class="text-center">#</th>
		                                    <th class="text-center">Nombre</th>
		                                    <th class="text-center">Fecha</th>
		                                    <th class="text-center">Opciones</th>
		                                </tr>
		                            </thead>
		                        </table>
		                        
		                   	</div>
						</div>
                    </div>
                    <div class="tab-pane" id="btabs-anexos" role="tabpanel">
                    	<div class="block">
                            <div class="block-content block-content-full">
		                        <button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Nuevo</button>
		                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/be_tables_datatables.js -->
		                        <table class="table table-bordered table-striped table-vcenter table-sm datatable-tipos-documentos">
		                            <thead class="thead-default">
		                                <tr>
		                                    <th class="text-center">#</th>
		                                    <th class="text-center">Nombre</th>
		                                    <th class="text-center">Fecha</th>
		                                    <th class="text-center">Opciones</th>
		                                </tr>
		                            </thead>
		                        </table>
		                    </div>
		                </div>
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
    {{ Html::script('js/app-form.js') }}

@endpush

@push('js-custom')
    <script>
        var jPCatalogos = new function(){
            this.init = function(){
                this.initDataTables();
            }

            this.initDataTables = function() {
                jQuery('.datatable-tipos-documentosa').dataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url : '/panel/admin/catalogos/tipos-documentos/datatables',
                        type: 'POST'
                    },
                    columnDefs: [ { orderable: false, targets: [] } ],
                    pageLength: 100,
                    lengthMenu: [[10, 20, 50, 100],[10, 20, 50, 100]],
                    autoWidth: true
                });
            };
        };

        jPCatalogos.init();
    </script>
@endpush