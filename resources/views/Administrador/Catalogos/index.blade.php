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
                                {{ Auth::user()->USUA_NOMBRE }}
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
                                    <tbody>
                                        @foreach($tiposDocumentos as $tipo)
                                        <tr>
                                            <td class="font-w600 text-center">{{ $tipo->getCodigo() }}</td>
                                            <td>{{ $tipo->getNombre() }}</td>
                                            <td class="text-center d-none d-sm-table-cell">
                                                <span class="badge badge-primary">Activo</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-rounded btn-noborder btn-outline-success" data-toggle="tooltip" title="Modificar">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-rounded btn-noborder btn-outline-danger" data-toggle="tooltip" title="Eliminar">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
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
    {{ Html::script('assets/js/plugins/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}
@endpush

@push('js-custom')
    <script>
        var jPCatalogos = new function(){
            this.init = function(){
                this.initDataTables();
            }

            this.initDataTables = function() {
                jQuery('#datatable-tipos-documentos').dataTable({
                    columnDefs: [ { orderable: false, targets: [ 3 ] } ],
                    pageLength: 1,
                    lengthMenu: [[1, 2, 3, 4], [1, 2, 3, 4]],
                    autoWidth: false
                });
            };
        };

        jPCatalogos.init();
    </script>
@endpush