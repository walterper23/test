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
        <a class="breadcrumb-item" href="{{ url('panel/documentos') }}">Documentos</a>
        <span class="breadcrumb-item active">Recibidos</span>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-0">
            <!-- Collapsible Inbox Navigation -->
            {{-- $conteoDocumentos --}}
            <!-- END Collapsible Inbox Navigation -->

        </div>
        <div class="col-md-9">
            <!-- Message List -->
            <div class="block">
                <div class="block-header block-header-default">
                    <!--div class="block-title">
                        <div class="push">
                            <button type="button" class="btn btn-rounded btn-alt-secondary">
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
                    </div-->
                    <div class="block-options">
                        <strong>1 - 10</strong> de <strong>23</strong>
                        <button type="button" class="btn-block-option" data-toggle="block-option">
                            <i class="si si-arrow-left"></i>
                        </button>
                        <button type="button" class="btn-block-option" data-toggle="block-option">
                            <i class="si si-arrow-right"></i>
                        </button>
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                    </div>
                </div>
            </div>

            @foreach( $documentos2 as $documento )
                    
                <div class="block">
                    <div class="block-content block-content-full ribbon ribbon-primary">
                        <div class="ribbon-box">Tipo de Documento</div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="font-w600">{{ $documento -> getNumero() }}</p>
                                <p>{{ $documento -> Detalle -> getDescripcion() }}</p>
                                <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="block-option">
                                    <i class="fa fa-fw fa-clipboard"></i> Anexos
                                </button>
                                <button type="button" class="btn btn-sm btn-alt-danger" data-toggle="block-option">
                                    <i class="fa fa-fw fa-file-pdf-o"></i> Escaneos <span class="badge badge-pill badge-danger">3</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <p class="font-w600">ÚLTIMO ESTADO:</p>
                                <p>Documento recepcionado por oficialía de partes</p>
                                <p>Documento recepcionado por oficialía de partes</p>
                            </div>
                            <div class="col-md-6">
                                <hr>
                                <div class="font-size-sm text-muted"><i class="fa fa-fw fa-sitemap"></i> Nombre de la dirección</div>
                                <div class="font-size-sm text-muted"><i class="fa fa-fw fa-sitemap"></i> Nombre del departamento</div>
                            </div>
                            <div class="col-md-6">
                                <hr>
                                <div class="font-size-sm text-muted"><i class="fa fa-fw fa-user"></i> Nombre de la persona</div>
                                <div class="font-size-sm text-muted"><i class="fa fa-fw fa-calendar"></i> {{ date('Y-m-d h:i:s a') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

            <div class="block">
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
    
                            @foreach( $documentos as $documento )

                            <tr class="ribbon ribbon-primary">
                                <div class="ribbon-box">$99</div>
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
                    
                    <table class="js-table-checkable table table-hover table-vcenter">
                        <tbody>
    
                            @foreach( $documentos2 as $documento )

                            <tr>
                                <td class="d-none d-sm-table-cell font-w600" style="width: 50px;">{{ $documento -> DOCU_NUMERO_FICHA }}</td>
                                <td>
                                    <a class="font-w600" href="{{ url('recepcion/documentos/'.$documento -> DOCU_DOCUMENTO.'/seguimiento') }}">{{ $documento -> DOCU_NUMERO_OFICIO }}</a>
                                    <div class="text-muted mt-5">{{ $documento -> DOCU_DESCRIPCION }}</div>
                                </td>
                                <td>
                                    <div class=" mt-5">{{ optional($documento -> estadoDocumento) -> ESDO_NOMBRE }}</div>
                                </td>
                                <td class="text-center" style="width: 120px;">
                                    <a href="{{ url('recepcion/documentos/'.$documento -> DOCU_DOCUMENTO.'/seguimiento') }}" class="btn btn-primary"><i class="fa fa-eye"></i> Ver seguimiento</a>
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