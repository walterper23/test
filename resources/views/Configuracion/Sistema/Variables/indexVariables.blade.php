@extends('app.layoutMaster')

@section('title', title('Configuración de variables') )

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('configuracion/usuarios') }}">Sistema</a>
        <span class="breadcrumb-item active">Variables</span>
    </nav>
@endsection

@section('content')
    <div class="block block-bordered block-mode-loading-refresh" id="context-{{ $form_id }}">
        <ul class="nav nav-tabs nav-tabs-alt nav-tabs-block align-items-center" data-toggle="tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#btabswo-static-one">Sistema</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#btabswo-static-two">Institución</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#btabswo-static-three">Adicional</a>
            </li>
        </ul>
        {{ Form::open(['url'=>$url_send_form,'id'=>$form_id,'method'=>'POST']) }}
        <div class="block-content tab-content">
            <div class="tab-pane active" id="btabswo-static-one" role="tabpanel">
                <div class="col-md-6">
                    {!! Field::text('sistema_nombre',config_var('Sistema.Nombre'),['label'=>'Nombre del Sistema']) !!}
                    {!! Field::text('sistema_siglas',config_var('Sistema.Siglas'),['label'=>'Siglas del Sistema']) !!}
                </div>
            </div>
            <div class="tab-pane" id="btabswo-static-two" role="tabpanel">
                <div class="col-md-6">
                    {!! Field::text('institucion_nombre',config_var('Institucion.Nombre'),['label'=>'Nombre de la Dependencia']) !!}
                    {!! Field::text('institucion_siglas',config_var('Institucion.Siglas'),['label'=>'Siglas de la Dependencia']) !!}
                    {!! Field::text('institucion_direccion',config_var('Institucion.Direccion'),['label'=>'Dirección de la Dependencia']) !!}
                    {!! Field::text('institucion_telefonos',config_var('Institucion.Telefonos'),['label'=>'Teléfonos de la dependencia']) !!}
                </div>
            </div>
            <div class="tab-pane" id="btabswo-static-three" role="tabpanel">
                <div class="col-md-6">
                    {!! Field::text('variable','valor',['label'=>'Label']) !!}
                    {!! Field::text('variable','valor',['label'=>'Label']) !!}
                </div>
            </div>
        </div>
        {{ Form::close() }}
        <div class="block-content block-content-full block-content-sm bg-body-light">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button class="btn btn-default" id="btn-cancel"><i class="fa fa-fw fa-times text-danger"></i> Cancelar</button>
                    <button class="btn btn-primary" id="btn-ok"><i class="fa fa-fw fa-floppy-o"></i> Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
@endsection