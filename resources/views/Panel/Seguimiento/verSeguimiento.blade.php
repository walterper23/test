@extends('app.layoutMaster')

@section('title')
	{{ title('Seguimiento de documento') }}
@endsection

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="{{ url() -> previous() }}"><i class="fa fa-server"></i> Panel de trabajo</a>
        <a class="breadcrumb-item" href="{{ url() -> previous() }}">Documentos</a>
        <a class="breadcrumb-item" href="javascript:void(0)">Documento # {{ $documento -> getCodigo() }}</a>
        <span class="breadcrumb-item active">Seguimiento # {{ $seguimiento -> getCodigo(5) }}</span>
    </nav>
@endsection

@section('content')
    <!-- Timeline Activity -->
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title"><i class="fa fa-fw fa-history"></i> Historial de Cambios de Estados del Documento #{{ $documento -> getCodigo() }}</h3>
            <div class="block-options">
                <button type="button" class="btn btn-sm btn-danger" onclick="hPanel.cambiarEstado({{ $seguimiento -> getKey() }})">
                    <i class="fa fa-fw fa-flash"></i> Cambiar estado
                </button>
                <button type="button" class="btn btn-sm btn-info" data-toggle="block-option" data-action="fullscreen_toggle"></button>
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
                            <td>{{ $detalle -> getObservaciones()  }}</td>
                            <td class="bg-primary text-white font-w700">Opciones</td>
                            <td>
                                 @if (! empty($seguimiento -> DETA_ANEXOS) )
                                <button type="button" class="btn btn-sm btn-rounded btn-alt-primary" onclick="hPanel.verAnexos({{ $seguimiento -> Documento -> getKey()  }})">
                                    <i class="fa fa-fw fa-clipboard"></i> Anexos
                                </button>
                                @endif
                                @if ($seguimiento -> Escaneos -> count() > 0 )
                                <button type="button" class="btn btn-sm btn-rounded btn-alt-danger" onclick="hPanel.verEscaneos({{ $seguimiento -> Documento -> getKey()  }})">
                                    <i class="fa fa-fw fa-clipboard"></i> Escaneos <span class="badge badge-pill badge-danger">{{ $seguimiento -> Escaneos -> count() }}</span>
                                </button>
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
                            <i class="list-timeline-icon fa fa-flash bg-danger"></i>
                            <div class="list-timeline-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="font-w700">
                                            <span class="text-danger">#{{ $seguimiento -> getCodigo(5) }}</span>
                                            :: {{ $seguimiento -> EstadoDocumento -> getNombre() }}
                                        </p>
                                        <i class="fa fa-fw fa-comment-o"></i> <span class="font-w600">Observaciones:</span> {{ $seguimiento -> getObservacion() }}
                                    </div>
                                    <div class="col-md-6">
                                        <p>
                                            <a href="javascript:void(0)">
                                                <i class="fa fa-fw fa-sitemap"></i> {{ $seguimiento -> DireccionOrigen -> getNombre() }}
                                            </a>
                                        </p>
                                        <p>
                                            @if (! is_null($seguimiento -> DepartamentoOrigen))
                                            <a href="javascript:void(0)">
                                                <i class="fa fa-fw fa-sitemap"></i> {{ $seguimiento -> DepartamentoOrigen -> getNombre() }}
                                            </a>
                                            @else
                                                <div class="text-muted"><i class="fa fa-fw fa-sitemap"></i></div>
                                            @endif
                                        </p>
                                        <div class="font-size-sm text-muted"><i class="fa fa-fw fa-user"></i>
                                            {{ trim(sprintf('%s :: %s %s',$seguimiento -> USDE_NO_TRABAJADOR, $seguimiento -> USDE_NOMBRES,$seguimiento -> USDE_APELLIDOS)) }}
                                        </div>
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
@endsection

@push('js-script')
    {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
    {{ Html::script('js/helpers/panel.helper.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">


</script>
@endpush