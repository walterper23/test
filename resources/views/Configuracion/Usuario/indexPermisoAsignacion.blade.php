@extends('app.layoutMaster')

@section('title')
    {{ title('Configuración de permisos y asignaciones de usuarios') }}
@endsection

@push('css-style')
    {{ Html::style('js/plugins/select2/select2.min.css') }}
    {{ Html::style('js/plugins/select2/select2-bootstrap.min.css') }}
@endpush

@section('breadcrumb')
    <nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <a class="breadcrumb-item" href="{{ url('configuracion/usuarios') }}">Usuarios</a>
        <span class="breadcrumb-item active">Permisos y Asignaciones</span>
    </nav>
@endsection

@section('content')
    <div class="block block-bordered">
        <ul class="nav nav-tabs nav-tabs-alt nav-tabs-block align-items-center" data-toggle="tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#btabswo-static-one">Permisos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#btabswo-static-two">Asignaciones</a>
            </li>
            <li class="nav-item ml-auto">
                <div class="block-options mr-15">
                <a href="{{ url('recepcion/documentos/nueva-recepcion') }}" class="btn-block-option">
                    <i class="fa fa-plus"></i> Nueva recepci&oacute;n
                </a>
                <div class="dropdown">
                    <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Opciones</button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="javascript:void(0)">
                            <i class="fa fa-fw fa-bell mr-5"></i>News
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0)">
                            <i class="fa fa-fw fa-pencil mr-5"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>
            </li>
        </ul>
        <div class="block-content tab-content">
            <div class="row">
                <div class="col-md-6">
                    {!! Field::selectTwo('usuario',$userKey,['label'=>'Usuario'], $usuarios) !!}
                </div>
            </div>
            <div class="tab-pane active" id="btabswo-static-one" role="tabpanel">
                <div class="row">
                    @foreach( $recursos as $recurso )
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-12 text-danger">{{ $recurso -> getNombre() }}</label>
                            <div class="col-12">
                                @foreach( $recurso -> permisos as $permiso )

                                @php $checked = in_array($permiso -> getKey(), $permisosUsuario) ? 'checked' : '' @endphp

                                <div class="custom-control custom-checkbox mb-5">
                                    <input class="custom-control-input" type="checkbox" name="permisos[]" id="permiso-{{ $permiso -> getKey() }}" value="{{ $permiso -> getKey() }}" {{ $checked }}>
                                    <label class="custom-control-label" for="permiso-{{ $permiso -> getKey() }}">{{ $permiso -> getDescripcion() }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="tab-pane" id="btabswo-static-two" role="tabpanel">
                <div class="row">
                    @foreach( $direcciones as $direccion )
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox mb-5">
                                    <input class="custom-control-input" type="checkbox" name="direcciones[]" id="direccion-{{ $direccion -> getKey() }}" value="{{ $direccion -> getKey() }}">
                                    <label class="custom-control-label text-danger" for="direccion-{{ $direccion -> getKey() }}">{{ $direccion -> getNombre() }}</label>
                                </div>
                                @foreach( $direccion -> DepartamentosExistentes as $departamento )
                                <div class="custom-control custom-checkbox mb-5 ml-20">
                                    <input class="custom-control-input" type="checkbox" name="departamentos[]" id="departamento-{{ $departamento -> getKey() }}" value="{{ $departamento -> getKey() }}">
                                    <label class="custom-control-label" for="departamento-{{ $departamento -> getKey() }}">{{ $departamento -> getNombre() }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="block-content block-content-full block-content-sm bg-body-light">
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-default"><i class="fa fa-fw fa-times text-danger"></i> Cancelar</button>
                    <button class="btn btn-primary pull-right"><i class="fa fa-fw fa-floppy-o"></i> Guardar cambios</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js-script')
    {{ Html::script('js/plugins/select2/select2.full.min.js') }}
@endpush

@push('js-custom')
    <script type="text/javascript">
        Codebase.helper('select2');
    </script>
@endpush