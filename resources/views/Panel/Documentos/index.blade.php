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
        {
            $url = sprintf('%s&read=1',$url);
        }

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
                        <strong>1 - {{ sizeof($documentos) }}</strong> de <strong>{{ sizeof($documentos) }}</strong>
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

            @forelse ($documentos as $seguimiento)
            <div class="block">
                <div class="block-content block-content-full ribbon ribbon-bookmark ribbon-{{ $seguimiento -> SYTD_RIBBON_COLOR }}">
                    <div class="ribbon-box">{{ $seguimiento -> SYTD_NOMBRE }}</div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-12">
                                    <p class="font-w700">{{ $seguimiento -> Documento -> getNumero() }}</p>
                                    <p>{{ $seguimiento -> DETA_DESCRIPCION }}</p>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-sm btn-rounded btn-alt-primary" onclick="hPanel.verAnexos({{ $seguimiento -> Documento -> getKey()  }})">
                                        <i class="fa fa-fw fa-clipboard"></i> Anexos
                                    </button>
                                    <button type="button" class="btn btn-sm btn-rounded btn-alt-danger" onclick="hPanel.verEscaneos({{ $seguimiento -> Documento -> getKey()  }})">
                                        <i class="fa fa-fw fa-clipboard"></i> Escaneos <span class="badge badge-pill badge-danger"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-12">
                                    <p class="font-w700">ÚLTIMO ESTADO:</p>
                                    <p>{{ $seguimiento -> EstadoDocumento -> getNombre() }}</p>
                                    <p><span class="font-w600">Observaciones:</span> {{ $seguimiento -> getObservacion() }}</p>
                                </div>
                                <div class="col-12">
                                    <a class="btn btn-sm btn-success pull-right" href="{{ url_ver_seguimiento( $seguimiento ) }}">
                                        <i class="fa fa-fw fa-paper-plane"></i> Ver seguimiento
                                    </a>
                                    @can('SEG.CAMBIAR.ESTADO')
                                    <button class="btn btn-sm btn-danger pull-right mr-5" onclick="hPanel.cambiarEstado({{ $seguimiento -> getKey() }})"><i class="fa fa-fw fa-flash"></i> Cambiar estado</button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-5">
                                    <hr>
                                    <div class="font-size-sm text-muted"><i class="fa fa-fw fa-sitemap"></i> {{ $seguimiento -> DireccionOrigen -> getNombre() }}</div>
                                    <div class="font-size-sm text-muted"><i class="fa fa-fw fa-sitemap"></i> {{ optional($seguimiento -> DepartamentoOrigen) -> getNombre() }}</div>
                                </div>
                                <div class="col-md-5">
                                    <hr>
                                    <div class="font-size-sm text-muted"><i class="fa fa-fw fa-user"></i> {{ trim(sprintf('%s %s',$seguimiento -> USDE_NOMBRES,$seguimiento -> USDE_APELLIDOS)) }}</div>
                                    <div class="font-size-sm text-muted"><i class="fa fa-fw fa-calendar"></i> {{ $seguimiento -> presenter() -> getFechaSeguimiento() }}</div>
                                </div>
                                <div class="col-md-2">
                                    <hr>
                                    <div class="font-size-sm text-muted text-right">
                                        <a href="javascript:void(0)" onclick="hPanel.marcarImportante(this, {{ $seguimiento -> Documento -> getKey() }})">
                                            @if ($seguimiento -> importante)
                                                <i class="fa fa-fw fa-star text-warning"></i>
                                            @else
                                                <i class="fa fa-fw fa-star-o"></i>
                                            @endif
                                        </a>
                                        <i class="fa fa-fw fa-file-o"></i> # {{ $seguimiento -> Documento -> getCodigo() }}
                                    </div>
                                    <div class="font-size-sm text-muted text-right" title="Seguimiento # {{ $seguimiento -> getCodigo(5) }}"><i class="fa fa-fw fa-flash"></i> # {{ $seguimiento -> getCodigo(5) }}</div>
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