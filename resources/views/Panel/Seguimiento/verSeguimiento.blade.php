@extends('app.layoutMaster')

@section('title', title('Seguimiento de documento') )

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="{{ url() -> previous() }}"><i class="fa fa-server"></i> Panel de trabajo</a>
        <a class="breadcrumb-item" href="{{ url() -> previous() }}">Documentos</a>
        <a class="breadcrumb-item" href="javascript:void(0)">Documento # {{ $documento -> getCodigo() }}</a>
        <span class="breadcrumb-item active">Seguimiento # {{ $seguimiento -> getCodigo() }}</span>
    </nav>
@endsection

@section('content')
    <!-- Timeline Activity -->
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                <i class="fa fa-fw fa-history"></i>
                Historial de Cambios de Estados del Documento <b>#{{ $documento -> getCodigo() }}</b>
                @if( $seguimiento -> Documento -> recepcionado() )
                <span class="badge badge-primary">Recepcionado</span>
                @elseif( $seguimiento -> Documento -> enSeguimiento() )
                <span class="badge badge-danger">En seguimiento</span>
                @elseif( $seguimiento -> Documento -> finalizado() )
                <span class="badge badge-success">Finalizado</span>
                @elseif( $seguimiento -> Documento -> rechazado() )
                <span class="badge badge-warning">Rechazado</span>
                @endif
            </h3>
            <div class="block-options">
                @can('SIS.ADMIN.ESTA.DOC')
                <button type="button" class="btn btn-sm btn-danger" onclick="hPanel.nuevoEstado()">
                    <i class="fa fa-fw fa-flash"></i> Nuevo estado
                </button>
                @endcan
                @if (user() -> can('SEG.CAMBIAR.ESTADO') && ! $seguimiento -> Documento -> finalizado() && ! $seguimiento -> Documento -> rechazado())
                <button type="button" class="btn btn-sm btn-danger" onclick="hPanel.cambiarEstado({{ $documento -> getKey() }})">
                    <i class="fa fa-fw fa-history"></i> Cambiar estado
                </button>
                @endif
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-12">
                    <table class="table table-vcenter table-sm">
                        <tr>
                            <td width="15%" class="bg-primary text-white font-w700 font-w700">Tipo de documento</td>
                            <td width="35%">{{ $documento -> TipoDocumento -> getNombre() }}</td>
                            <td width="15%" class="bg-primary text-white font-w700">Responsable</td>
                            <td width="35%">{{ $detalle -> getResponsable() }}</td>
                        </tr>
                        <tr>
                            <td class="bg-primary text-white font-w700">Nó. de documento</td>
                            <td>{{ $documento -> getNumero()  }}</td>
                            <td class="bg-primary text-white font-w700">Municipio</td>
                            <td>{{ $detalle -> Municipio -> getNombre() }}</td>
                        </tr>
                        <tr>
                            <td class="bg-primary text-white font-w700">Asunto / Descripción</td>
                            <td>{{ $detalle -> getDescripcion() }}</td>
                            <td class="bg-primary text-white font-w700">Recepción</td>
                            <td>{{ $detalle -> getFechaRecepcion() }}</td>
                        </tr>
                        <tr>
                            <td class="bg-primary text-white font-w700">Observaciones</td>
                            @if (! empty($detalle -> getObservaciones()))
                            <td>{{ $detalle -> getObservaciones()  }}</td>
                            @else
                            <td class="font-size-sm text-muted">- Sin observaciones -</td>
                            @endif
                            <td class="bg-primary text-white font-w700">Opciones</td>
                            <td>
                                @if (! empty($detalle -> DETA_ANEXOS) || $seguimiento -> Escaneos -> count() > 0)
                                    @if (! empty($detalle -> DETA_ANEXOS) )
                                    <button type="button" class="btn btn-sm btn-rounded btn-alt-primary" onclick="hPanel.verAnexos({{ $seguimiento -> Documento -> getKey()  }})">
                                        <i class="fa fa-fw fa-clipboard"></i> Anexos
                                    </button>
                                    @endif
                                    @if ($seguimiento -> Escaneos -> count() > 0 )
                                    <button type="button" class="btn btn-sm btn-rounded btn-alt-danger" onclick="hPanel.verEscaneos({{ $seguimiento -> Documento -> getKey()  }})">
                                        <i class="fa fa-fw fa-clipboard"></i> Escaneos <span class="badge badge-pill badge-danger">{{ $seguimiento -> Escaneos -> count() }}</span>
                                    </button>
                                    @endif
                                @else
                                    <div class="font-size-sm text-muted">- El documento no contiene anexos ni escaneos -</div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-12">
                    <br>
                    <ul class="list list-timeline list-timeline-modern pull-t">
                        @foreach( $seguimientos as $seguimiento )
                        <li>
                            <div class="list-timeline-time">
                                <i class="fa fa-fw fa-calendar"></i> {{ $seguimiento -> presenter() -> getFechaSeguimiento() }}
                            </div>
                            @if ($loop -> first)
                                @if( $seguimiento -> Documento -> finalizado() )
                                    <i class="list-timeline-icon fa fa-flag-checkered bg-success" title="Documento finalizado"></i>
                                @elseif( $seguimiento -> Documento -> rechazado() )
                                    <i class="list-timeline-icon fa fa-flag-checkered bg-warning" title="Documento rechazado"></i>
                                @else
                                    <i class="list-timeline-icon fa fa-flash bg-danger" title="Documento en seguimiento"></i>
                                @endif
                            @elseif ($loop -> last)
                                <i class="list-timeline-icon fa fa-folder-open bg-primary" title="Documento recepcionado"></i>
                            @else 
                                <i class="list-timeline-icon fa fa-flash bg-danger" title="Documento en seguimiento"></i>
                            @endif
                            <div class="list-timeline-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="font-w700">
                                            <span class="text-danger">#{{ $seguimiento -> getCodigo() }}</span>
                                            :: {{ $seguimiento -> EstadoDocumento -> getNombre() }}
                                        </p>
                                        <i class="fa fa-fw fa-comment-o"></i> <span class="font-w600">Observaciones:</span> {{ $seguimiento -> getObservacion() }}
                                    </div>
                                    <div class="col-md-6">
                                        <p>
                                            @if (is_null($seguimiento -> DepartamentoOrigen))
                                            <a href="javascript:void(0)">
                                                <span class="badge badge-secondary"><i class="fa fa-fw fa-legal"></i> {{ $seguimiento -> DireccionOrigen -> getNombre() }}</span>
                                            </a>
                                            @else
                                            <a href="javascript:void(0)">
                                                <span class="badge badge-warning"><i class="fa fa-fw fa-sitemap"></i> {{ $seguimiento -> DepartamentoOrigen -> getNombre() }}</span>
                                            </a>
                                            @endif
                                            <a href="javascript:void(0)">
                                                <span class="badge badge-primary"><i class="fa fa-fw fa-user"></i>
                                                    {{ trim(sprintf('%s :: %s %s',$seguimiento -> USDE_NO_TRABAJADOR, $seguimiento -> USDE_NOMBRES,$seguimiento -> USDE_APELLIDOS)) }}
                                                </span>
                                            </a>
                                        </p>
                                    </div>
                                    <div class="col-md-12"> 
                                        @if( $seguimiento -> Dispersiones -> count() > 0 )
                                            <p class="font-size-xs">El cambio de estado fue disperso a las siguientes direcciones y departamentos: </p>
                                            @foreach($seguimiento -> Dispersiones as $dispersion)
                                                @if (is_null($dispersion -> Departamento))
                                                <a href="javascript:void(0)">
                                                    <span class="badge badge-secondary"><i class="fa fa-fw fa-legal"></i> {{ $dispersion -> Direccion -> getNombre() }}</span>
                                                </a>
                                                @else
                                                <a href="javascript:void(0)">
                                                    <span class="badge badge-warning"><i class="fa fa-fw fa-sitemap"></i> {{ $dispersion -> Departamento -> getNombre() }}</span>
                                                </a>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Timeline Activity -->

    <div class="modal fade" id="modal-popout" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary">
                        <h3 class="block-title"><i class="fa fa-fw fa-sitemap"></i> Direcciones y Departamentos</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal-popout" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        @include('Panel.Seguimiento.direccionesDepartamentos',[$direcciones])
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal-popout">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js-script')
    {{ Html::script('js/helpers/panel.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush