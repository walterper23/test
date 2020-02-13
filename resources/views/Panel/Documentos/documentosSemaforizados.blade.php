@extends('app.layoutMaster')

@section('title', title('Documentos semaforizados') )

@include('vendor.plugins.datatables')

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="{{ url() -> previous() }}"><i class="fa fa-server"></i> Panel de trabajo</a>
        <a class="breadcrumb-item" href="{{ url() -> previous() }}">Documentos
        <span class="breadcrumb-item active">Semaforizados</span>
    </nav>
@endsection

@section('content')
    <div class="block block-themed block-mode-loading-refresh">
        <div class="block-header bg-earth">
            <h3 class="block-title"><i class="fa fa-fw fa-flag mr-5"></i> Documentos semaforizados</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option d-none d-sm-inline" onclick="hSemaforo.reload('dataTableBuilder')">
                    <i class="fa fa-refresh"></i> Actualizar
                </button>
                <div class="dropdown">
                    <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Opciones</button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="javascript:void(0)" onclick="hSemaforo.reload('dataTableBuilder')">
                            <i class="fa fa-fw fa-refresh mr-5"></i>Actualizar registros
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive font-size-sm">
                {{ $table -> html() }}
            </div>
        </div>
    </div>
@endsection

@push('js-script')
    {{ Html::script('js/helpers/semaforo.helper.js') }}
    {{ Html::script('js/app-form.js') }}
@endpush

@push('js-custom')
	{!! $table->javascript() !!}
    @if( request() -> has('open') && request() -> has('view') )
    <script type="text/javascript">
        setTimeout(function(){
            hSemaforo.verSeguimiento({{ request('open') }},{{ request('view') }});
        },1000);
    </script>
    @endif
@endpush