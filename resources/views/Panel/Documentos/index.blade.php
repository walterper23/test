@extends('app.layoutMaster')

@section('title', title($title) )

@include('vendor.plugins.datatables')

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="{{ url()->previous() }}"><i class="fa fa-server"></i> Panel de trabajo</a>
        <a class="breadcrumb-item" href="{{ url()->previous() }}">Documentos</a>
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
        $url = sprintf('/panel/documentos/seguimiento?search=%d',$seguimiento->getKey());

        if (! $seguimiento->leido())
            $url = sprintf('%s&read=1',$url);

        return $url;
    }

    function filter_view($type, $count)
    {
        return sprintf('%s %s',$type,badge($count));
    }

    $seccion1 = 'col-xs-12 col-md-6 col-lg-3';
    $seccion2 = 'col-xs-12 col-md-6 col-lg-3';
    $seccion3 = 'col-xs-12 col-md-6 col-lg-3';
    $seccion4 = 'col-xs-12 col-md-6 col-lg-3';

    if (request('view','all') == 'finished') {
        $seccion1 = 'col-xs-12 col-md-6 col-lg-5';
        $seccion2 = 'col-xs-12 col-md-6 col-lg-4';
        $seccion3 = false;
    }

@endphp

@section('content')
    <div class="row" id="panel-documentos">
        <div class="col-12">
            <!-- Message List -->
            <div class="block">
                <div class="block-header block-header-default">
                    <div class="block-title">
                        <div class="push">
                            {{ Form::open(['method'=>'GET']) }}
                            <div class="row">
                                <div class="col-md-2">
                                    {{ Form::hidden('view',request()->get('view','all')) }}
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-sm btn-secondary">{!! filter_view($view, $paginador->total_documentos) !!}</button>
                                        </div>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(80px, 34px, 0px);">
                                                <a class="dropdown-item" href="{{ url('panel/documentos') . '?'. http_build_query(['search'=>$search,'view'=>'pending'])  }}">
                                                    <i class="fa fa-fw fa-folder"></i> Por turnar
                                                </a>
                                                <a class="dropdown-item" href="{{ url('panel/documentos') . '?'. http_build_query(['search'=>$search,'view'=>'moved'])  }}">
                                                    <i class="fa fa-fw fa-mail-forward text-danger"></i> Turnados
                                                </a>
                                                <a class="dropdown-item" href="{{ url('panel/documentos') . '?'. http_build_query(['search'=>$search,'view'=>'all']) }}">
                                                    <i class="fa fa-fw fa-files-o text-primary"></i> Todos
                                                </a>
                                                <a class="dropdown-item" href="{{ url('panel/documentos') . '?'. http_build_query(['search'=>$search,'view'=>'important']) }}">
                                                    <i class="fa fa-fw fa-star text-warning"></i> Importantes
                                                </a>
                                                <a class="dropdown-item" href="{{ url('panel/documentos') . '?'. http_build_query(['search'=>$search,'view'=>'rejected']) }}">
                                                    <i class="fa fa-fw fa-thumbs-down text-danger"></i> Rechazados
                                                </a>
                                                <a class="dropdown-item" href="{{ url('panel/documentos') . '?'. http_build_query(['search'=>$search,'view'=>'finished']) }}">
                                                    <i class="fa fa-fw fa-flag-checkered text-success"></i> Concluidos
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ url('panel/documentos') . '?'. http_build_query(['search'=>$search,'view'=>'archived']) }}">
                                                    <i class="fa fa-fw fa-archive text-primary"></i> Archivados
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-sm btn-secondary">Folio</button>
                                        </div>
                                        <input type="search" class="form-control form-control-sm" id="search_folio" name="search_folio" placeholder="Ej. {{ date('Y')-1 }}-23, 27" value="{{ $search_folio }}" title="Folio de documento">
                                        <input type="search" class="form-control form-control-sm" id="search" name="search" placeholder="Tipo documento, Nó. documento, Nó. expediente, Asunto..." value="{{ $search }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-sm btn-secondary"><i class="fa fa-search"></i> Buscar</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 text-right">
                                    @can('SIS.ADMIN.ESTA.DOC')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="hPanel.nuevoEstado()">
                                        <i class="fa fa-fw fa-flash"></i> Nuevo estado
                                    </button>
                                    @endcan
                                    <div class="btn-group btn-group-sm" role="group">
                                        @include('Panel.Documentos.paginacionDocumentos')
                                    </div>
                                    <a href="#" class="btn btn-sm btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Recargar documentos" onclick="location.reload()">
                                        <i class="fa fa-refresh fa-fw"></i>
                                    </a>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>

            @forelse ($paginador->getDocumentos() as $seguimiento)
            @php
                $documento = $seguimiento->Documento;
            @endphp
            {{-- <div class="block {{ (! $seguimiento->leido()) ? 'bg-info-light' : '' }}"> --}}
            <div class="block">
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="{{ $seccion1 }}">
                            <div class="row">
                                <div class="col-12">
                                    <i class="fa fa-fw fa-hashtag"></i>
                                    <span class="font-w700">{{ $documento->AcuseRecepcion->getNumero() }}</span>
                                    <span class="badge badge-{{ $seguimiento->SYTD_RIBBON_COLOR }}">
                                        {{ $documento->TipoDocumento->getNombre() }}
                                        @if ( $documento->getTipoDocumento() == 1 /* Denuncia */)
                                            <span @can('DOC.CREAR.NO.EXPE') style="cursor: pointer;" onclick="hPanel.expediente({{ $documento->getKey() }})" @endcan>
                                            <i class="fa fa-fw fa-legal"></i>
                                            @if (! empty($seguimiento->DENU_NO_EXPEDIENTE) )
                                                {{ $seguimiento->DENU_NO_EXPEDIENTE }}
                                            @else
                                                <span title="La denuncia aún no tiene Nó. de Expediente asignado">_ _ _ _</span>
                                            @endif
                                        </span>
                                        @endif
                                    </span>
                                    <p><i class="fa fa-fw fa-file-text-o"></i><span class="font-w700">Nó. documento:</span> {{ $documento->getNumero() }}</p>
                                    <p><i class="fa fa-fw fa-calendar"></i><span class="font-w700">Fecha de Recepción:</span> {{ $seguimiento->DETA_FECHA_RECEPCION }}</p>
                                    <p><i class="fa fa-fw fa-file-text-o"></i><span class="font-w700">Asunto:</span> {{ $seguimiento->DETA_DESCRIPCION }}</p>
                                    @if (! empty($seguimiento->DETA_ANEXOS) )
                                    <button type="button" class="btn btn-sm btn-rounded btn-alt-primary" onclick="hPanel.verAnexos({{ $documento->getKey()  }})">
                                        <i class="fa fa-fw fa-clipboard"></i> Anexos
                                    </button>
                                    @endif
                                    @if ($seguimiento->Escaneos->count() > 0 )
                                    <button type="button" class="btn btn-sm btn-rounded btn-alt-danger" onclick="hPanel.verEscaneos({{ $documento->getKey()  }})">
                                        <i class="fa fa-fw fa-clipboard"></i> Escaneos <span class="badge badge-pill badge-danger">{{ $seguimiento->Escaneos->count() }}</span>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="{{ $seccion2 }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <a class="text-dark" href="{{ url_ver_seguimiento( $seguimiento ) }}">
                                        <p class="font-w700"><i class="fa fa-fw fa-flash text-danger"></i> {{ $seguimiento->EstadoDocumento->getNombre() }}</p>
                                        <span class="badge badge-success" title="Fecha y hora del cambio de estado"><i class="fa fa-fw fa-calendar"></i> {{ $seguimiento->presenter()->getFechaSeguimiento() }}</span>
                                    </a>
                                </div>
                                <div class="col-md-12">
                                    <span class="badge badge-secondary" title="Dirección origen"><i class="fa fa-fw fa-sitemap"></i> {{ $seguimiento->DireccionOrigen->getNombre() }}</span>
                                    @if ($seguimiento->DepartamentoOrigen)
                                    <span class="badge badge-warning" title="Departamento origen"><i class="fa fa-fw fa-sitemap"></i> {{ $seguimiento->DepartamentoOrigen->getNombre() }}</span>
                                    @endif
                                    <span class="badge badge-primary"><i class="fa fa-fw fa-user"></i>
                                        {{ trim(sprintf('%s :: %s %s',$seguimiento->USDE_NO_TRABAJADOR, $seguimiento->USDE_NOMBRES,$seguimiento->USDE_APELLIDOS)) }}
                                    </span>
                                    <p><span class="font-w600"><i class="fa fa-fw fa-comment-o"></i> Observaciones:</span> {{ $seguimiento->getObservacion() }}</p>
                                </div>
                            </div>
                        </div>
                        @if($seccion3)
                        <div class="{{ $seccion3 }}">
                            <div class="row">
                                <div class="col-md-12">
                                    @if($seguimiento->DireccionDestino)
                                    <span class="badge badge-secondary" title="Dirección destino"><i class="fa fa-fw fa-sitemap"></i> {{ $seguimiento->DireccionDestino->getNombre() }}</span>
                                    @endif
                                    @if ($seguimiento->DepartamentoDestino)
                                    <span class="badge badge-warning" title="Departamento destino"><i class="fa fa-fw fa-sitemap"></i> {{ $seguimiento->DepartamentoDestino->getNombre() }}</span>
                                    @endif
                                    @if($documento->enSeguimiento())
                                    <p><span class="font-w600"><i class="fa fa-fw fa-street-view"></i> Instrucción recibida:</span>
                                        @if( $seguimiento->getInstruccion() )
                                            {{ $seguimiento->getInstruccion() }}
                                        @else
                                            <span class="font-size-xs text-muted">- Ninguna -</span>
                                        @endif
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="{{ $seccion4 }} text-center section-options">
                            <div class="mb-10">
                                @if (!$documento->finalizado() && !$documento->rechazado()
                                    && user()->can('SEG.CAMBIAR.ESTADO') && in_array($seguimiento->getDireccionDestino(), session('DireccionesKeys')) )
                                <button type="button" class="btn btn-sm btn-rounded btn-alt-danger" onclick="hPanel.cambiarEstado({{ $documento->getKey()  }})">
                                    <i class="fa fa-fw fa-history"></i> Cambiar estado</span>
                                </button>
                                @endif
                                <a class="btn btn-sm btn-rounded btn-alt-success" href="{{ url_ver_seguimiento( $seguimiento ) }}">
                                    <i class="fa fa-fw fa-paper-plane"></i> Ver seguimiento</span>
                                </a>
                            </div>

                            @if ( user()->can('DOC.CREAR.NO.EXPE') && $documento->getTipoDocumento() == 1)
                            <div class="mb-10">
                                <button type="button" class="btn btn-sm btn-rounded btn-alt-danger" onclick="hPanel.expediente({{ $documento->getKey() }})">
                                    <i class="fa fa-fw fa-legal"></i> Asignar Nó. de expediente</span>
                                </button>
                            @endif

                            <div class="mb-10">
                                <button type="button" class="btn btn-sm btn-rounded btn-alt-warning" onclick="hPanel.marcarImportante(this, {{ $documento->getKey() }})">
                                    @if ($documento->importante())
                                        <i class="fa fa-fw fa-star text-warning star"></i> Importante
                                    @else
                                        <i class="fa fa-fw fa-star-o star"></i> Importante
                                    @endif
                                </button>
                                <button type="button" class="btn btn-sm btn-rounded btn-alt-secondary" onclick="hPanel.marcarArchivado(this, {{ $documento->getKey() }})">
                                    @if (! $documento->archivado() )
                                        <i class="fa fa-fw fa-archive archive"></i> <span id="arch">Archivar</span>
                                    @else
                                        <i class="fa fa-fw fa-archive archive text-primary"></i> <span id="arch">Desarchivar</span>
                                    @endif
                                </button>
                            </div>
                            {!! $seguimiento->Documento->EstadoDocumento->presenter()->getBadge() !!}
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

    @include('Panel.Seguimiento.direccionesDepartamentos',[$direcciones])                
    
@endsection

@push('js-script')
    {{ Html::script('js/helpers/panel.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush
