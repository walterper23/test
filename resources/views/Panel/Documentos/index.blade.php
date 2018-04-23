@extends('app.layoutMaster')

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
        <a class="breadcrumb-item" href="{{ url() -> previous() }}"><i class="fa fa-server"></i> Panel de trabajo</a>
        <a class="breadcrumb-item" href="{{ url() -> previous() }}">Documentos</a>
        <span class="breadcrumb-item active">{{ $title }}</span>
    </nav>
@endsection

@php
    function badge( $size )
    {
        return $size > 0 ? sprintf('<span class="badge badge-pill badge-primary">%d</span>',$size) : '';
    }

    function url_ver_seguimiento( $seguimiento )
    {
        $url = sprintf('panel/documentos/seguimiento?search=%d',$seguimiento -> getKey());

        if ($seguimiento -> leido === false)
            $url = sprintf('%s&read=1',$url);

        return url( $url );
    }
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Message List -->
            <div class="block">
                <div class="block-header block-header-default">
                    <div class="block-title">
                        <div class="push">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="btn-group" role="group" aria-label="Documentos a visualizar">
                                        <div class="btn-group show" role="group">
                                            <button type="button" class="btn btn-alt-secondary dropdown-toggle" id="btnGroupDrop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ver documentos</button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item" href="{{ url('panel/documentos?view=recents') }}">
                                                    <i class="fa fa-inbox mx-5"></i> Recientes {!! badge($recientes) !!}
                                                </a>
                                                <a class="dropdown-item" href="{{ url('panel/documentos?view=all') }}">
                                                    <i class="fa fa-fw fa-cubes mr-5"></i> Todos {!! badge($todos) !!}
                                                </a>
                                                <a class="dropdown-item" href="{{ url('panel/documentos?view=important') }}">
                                                    <i class="fa fa-fw fa-star mr-5"></i> Importantes {!! badge($importantes) !!}
                                                </a>
                                                <a class="dropdown-item" href="{{ url('panel/documentos?view=archived') }}">
                                                    <i class="fa fa-fw fa-archive mr-5"></i> Archivados {!! badge($archivados) !!}
                                                </a>
                                                <a class="dropdown-item" href="{{ url('panel/documentos?view=finished') }}">
                                                    <i class="fa fa-fw fa-flag-checkered mr-5"></i> Finalizados {!! badge($finalizados) !!}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="search" class="form-control" id="search" name="search" placeholder="Nó. documento, Nó. expediente, Asunto, Descripción...">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-secondary"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block-options">
                        @can('SIS.ADMIN.ESTA.DOC')
                        <button type="button" class="btn btn-danger" onclick="hPanel.nuevoEstado()">
                            <i class="fa fa-fw fa-flash"></i> Nuevo
                        </button>
                        @endcan
                        <strong>1 - {{ sizeof($documentos) }}</strong> de <strong>{{ sizeof($documentos) }}</strong>
                        <button type="button" class="btn-block-option" data-toggle="block-option">
                            <i class="si si-arrow-left"></i>
                        </button>
                        <button type="button" class="btn-block-option" data-toggle="block-option">
                            <i class="si si-arrow-right"></i>
                        </button>
                        <button type="button" class="btn-block-option" data-toggle="block-option">
                            <i class="si si-refresh"></i>
                        </button>
                    </div>
                </div>
            </div>

            @forelse ($documentos as $seguimiento)
            <div class="block {{ ($seguimiento -> leido === false) ? 'bg-info-light' : '' }}">
                <div class="block-content block-content-full ribbon ribbon-bottom ribbon-bookmark ribbon-{{ $seguimiento -> SYTD_RIBBON_COLOR }}">
                    <div class="ribbon-box">{{ $seguimiento -> SYTD_NOMBRE }}</div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-12">
                                    <p class="font-w700">{{ $seguimiento -> Documento -> getNumero() }}</p>
                                    <p>{{ $seguimiento -> DETA_DESCRIPCION }}</p>
                                </div>
                                <div class="col-12">
                                    <span><i class="fa fa-fw fa-calendar"></i> <b>Recepción:</b> {{ $seguimiento -> DETA_FECHA_RECEPCION }} </span>
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
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <a class="font-w700 text-primary-darker" title="Seguimiento #{{ $seguimiento -> getCodigo(5) }}" href="{{ url_ver_seguimiento( $seguimiento ) }}">
                                <span class="text-danger"><i class="fa fa-fw fa-flash"></i> #{{ $seguimiento -> getCodigo(5) }}</span> :: {{ $seguimiento -> EstadoDocumento -> getNombre() }}
                            </a>
                            <p><span class="font-w600"><i class="fa fa-fw fa-comment-o"></i> Observaciones:</span> {{ $seguimiento -> getObservacion() }}</p>
                        </div>
                        <div class="col-md-2 text-right section-options">
                            <div class="font-size-sm text-muted text-right" title="Documento #{{ $seguimiento -> Documento -> getCodigo() }}">
                                <a href="javascript:void(0)" onclick="hPanel.marcarImportante(this, {{ $seguimiento -> Documento -> getKey() }})">
                                    @if ($seguimiento -> importante)
                                        <i class="fa fa-fw fa-star text-warning star" title="Marcar como importante"></i>
                                    @else
                                        <i class="fa fa-fw fa-star-o star" title="Marcar como importante"></i>
                                    @endif
                                </a>
                                #{{ $seguimiento -> Documento -> getCodigo() }}
                                @if ($seguimiento -> leido === false)
                                    <i class="fa fa-fw fa-folder"></i>
                                @else
                                    <i class="fa fa-fw fa-folder-open-o"></i>
                                @endif
                                <div class="btn-group" role="group" aria-label="Opciones">
                                    <div class="btn-group show" role="group">
                                        <button type="button" class="btn btn-sm btn-alt-secondary dropdown-toggle" id="btnGroupDrop2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opciones</button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop2" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 94px, 0px); top: 0px; left: 40px; will-change: transform;">
                                            @if ($seguimiento -> Documento -> enSeguimiento() && user() -> can('SEG.CAMBIAR.ESTADO') )
                                            <a class="dropdown-item" href="#" onclick="hPanel.cambiarEstado({{ $seguimiento -> getKey() }})">
                                                <i class="fa fa-fw fa-flash text-danger"></i> Cambiar estado
                                            </a>
                                            @endif
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="hPanel.marcarImportante(this, {{ $seguimiento -> Documento -> getKey() }})">
                                            @if ($seguimiento -> importante)
                                                <i class="fa fa-fw fa-star text-warning star"></i> Importante
                                            @else
                                                <i class="fa fa-fw fa-star-o star"></i> Importante
                                            @endif
                                            </a>
                                            <a class="dropdown-item" href="{{ url_ver_seguimiento( $seguimiento ) }}">
                                                <i class="fa fa-fw fa-paper-plane text-success"></i> Ver seguimiento
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="hPanel.marcarArchivado(this, {{ $seguimiento -> Documento -> getKey() }})">
                                            @if (! $seguimiento -> Documento -> archivado() )
                                                <i class="fa fa-fw fa-archive archive"></i> <span id="arch">Archivar</span>
                                            @else
                                                <i class="fa fa-fw fa-archive archive text-primary"></i> <span id="arch">Desarchivar</span>
                                            @endif
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <br>{{ $seguimiento -> SYED_NOMBRE }}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-5">
                                    <hr>
                                    <div class="font-size-sm text-muted"><i class="fa fa-fw fa-sitemap"></i> {{ $seguimiento -> DireccionOrigen -> getNombre() }}</div>
                                    <div class="font-size-sm text-muted"><i class="fa fa-fw fa-sitemap"></i> {{ optional($seguimiento -> DepartamentoOrigen) -> getNombre() }}</div>
                                </div>
                                <div class="col-md-7">
                                    <hr>
                                    <div class="font-size-sm text-muted"><i class="fa fa-fw fa-user"></i> {{ trim(sprintf('%s :: %s %s',$seguimiento -> USDE_NO_TRABAJADOR, $seguimiento -> USDE_NOMBRES,$seguimiento -> USDE_APELLIDOS)) }}</div>
                                    <div class="font-size-sm text-muted"><i class="fa fa-fw fa-calendar"></i> {{ $seguimiento -> presenter() -> getFechaSeguimiento() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="block">
                <div class="block-content block-content-full">
                    <div class="font-size-h3 font-w600 py-30 mb-20 text-center border-b">
                        No se encontraron documentos
                    </div>
                </div>
            </div>
            @endforelse
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