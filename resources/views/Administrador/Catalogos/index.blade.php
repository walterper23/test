@extends('Tema.app')

@section('title')
    SIGESD :: Cat&aacute;logos
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
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
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
                                
                                <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/be_tables_datatables.js -->
                                <table class="table table-bordered table-striped table-vcenter table-sm" id="datatable-tipos-documentos">
                                    <thead class="thead-default">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center d-none d-sm-table-cell">Status</th>
                                            <th class="text-center">Opciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="btabs-anexos" role="tabpanel">
                        <h4 class="font-w400">Profile Content</h4>
                        <p>...</p>
                    </div>
                </div>
            </div>
            <!-- END Block Tabs Alternative Style -->
        </div>
    </div>
    <!-- END Block Tabs -->
@stop

@push('js-script')
    {{ Html::script('js/plugins/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('js/plugins/datatables/dataTables.bootstrap4.min.js') }}
@endpush

@push('js-custom')
    <script>
        var jPCatalogos = new function(){
            this.init = function(){
                this.initDataTables();
            }

            this.initDataTables = function() {
                jQuery('#datatable-tipos-documentos').dataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url : 'administrar/catalogos/tipos-documentos',
                        type: 'POST'
                    },
                    columns: [
                        { data: 'TIDO_TIPO_DOCUMENTO', name: 'TIDO_TIPO_DOCUMENTO' },
                        { data: 'TIDO_NOMBRE_TIPO', name: 'TIDO_NOMBRE_TIPO' },
                        { data: 'TIDO_CREATED_AT', name: 'TIDO_CREATED_AT' },
                        { data: 'TIDO_ENABLED', name: 'TIDO_ENABLED' },
                    ],
                    columnDefs: [ { orderable: false, targets: [3] } ],
                    pageLength: 100,
                    lengthMenu: [[10, 20, 50, 100],[10, 20, 50, 100]],
                    autoWidth: true
                });
            };
        };

        jPCatalogos.init();
    </script>
@endpush