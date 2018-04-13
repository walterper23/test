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
    <div class="block block-bordered block-mode-loading-refresh" id="context-{{ $form_id }}">
        <ul class="nav nav-tabs nav-tabs-alt nav-tabs-block align-items-center" data-toggle="tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#btabswo-static-one">Permisos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#btabswo-static-two">Asignaciones</a>
            </li>
            <li class="nav-item ml-auto">
                <div class="block-options mr-15">
                <button type="button" class="btn-block-option d-none d-sm-inline" onclick="hUsuario.new_('form-usuario','{{ url('configuracion/usuarios/nuevo') }}')">
                    <i class="fa fa-user-plus"></i> Nuevo usuario
                </button>
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
        {{ Form::open(['url'=>$url_send_form,'id'=>$form_id,'method'=>'POST']) }}
        {{ Form::hidden('action',2) }}
        <div class="block-content tab-content">
            <div class="row">
                <div class="col-md-6">
                    {!! Field::selectTwo('usuario',$userKey,['label'=>'Usuario'], $usuarios) !!}
                </div>
            </div>
            <div class="tab-pane active" id="btabswo-static-one" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success" role="alert">
                            <p class="mb-0">A continuación se enlistan los permisos disponibles para los usuarios. Seleccione uno o más permisos para asignarselos al usuario especificado.</p>
                        </div>
                    </div>
                    @foreach( $recursos as $recurso )
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-12"><p class="bg-success font-w700 text-white pl-5 pr-5">{{ $recurso -> getNombre() }}</p></label>
                            <div class="col-12">
                                @foreach( $recurso -> Permisos() -> orderBy('SYPE_DESCRIPCION') -> get() as $permiso )

                                @php $checked = in_array($permiso -> getKey(), $permisosUsuario) ? 'checked' : '' @endphp

                                <div class="custom-control custom-checkbox mb-5 ml-20">
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
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            <p class="mb-0">A continuación se enlistan las direcciones y sus departamentos. Seleccione las direcciones y/o departamentos que desea asignarle al usuario especificado.</p>
                            <p class="mb-o">Estas asignaciones permiten a los usuarios ver los documentos que llegan a las direcciones y/o departamentos que tenga asignado.</p>
                        </div>
                    </div>
                    @foreach( $direcciones as $direccion )

                    @php $checked_dir = in_array($direccion -> getKey(), $direccionesUsuario) ? 'checked' : '' @endphp

                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox mb-5">
                                    <input class="custom-control-input" type="checkbox" name="direcciones[]" id="direccion-{{ $direccion -> getKey() }}" value="{{ $direccion -> getKey() }}" {{ $checked_dir }}>
                                    <label class="custom-control-label bg-primary font-w700 text-white pl-5 pr-5" for="direccion-{{ $direccion -> getKey() }}">{{ $direccion -> getNombre() }}</label>
                                </div>
                                @foreach( $direccion -> DepartamentosExistentes() -> orderBy('DEPA_NOMBRE') -> get() as $departamento )
                                
                                @php $checked_dep = in_array($departamento -> getKey(), $departamentosUsuario) ? 'checked' : '' @endphp

                                <div class="custom-control custom-checkbox mb-5 ml-30">
                                    <input class="custom-control-input" type="checkbox" name="departamentos[]" id="departamento-{{ $departamento -> getKey() }}" value="{{ $departamento -> getKey() }}" {{ $checked_dep }}>
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
        {{ Form::close() }}
        <div class="block-content block-content-full block-content-sm bg-body-light">
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-default" id="btn-cancel"><i class="fa fa-fw fa-times text-danger"></i> Cancelar</button>
                    <button class="btn btn-primary pull-right" id="btn-ok"><i class="fa fa-fw fa-floppy-o"></i> Guardar cambios</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js-script')
    {{ Html::script('js/plugins/select2/select2.full.min.js') }}
    {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
    {{ Html::script('js/helpers/usuario.helper.js') }}
    {{ Html::script('js/app-form.js') }}
    {{ Html::script('js/app-alert.js') }}
@endpush

@push('js-custom')
<script type="text/javascript">
    'use strict';
    $.extend(new AppForm, new function(){

        this.context_   = '#context-{{ $form_id }}';
        this.form_      = '#{{$form_id}}';
        this.btnOk_     = '#btn-ok';
        this.btnCancel_ = '#btn-cancel';

        var checkboxPermisos      = $('input[name="permisos[]"]');
        var checkboxDirecciones   = $('input[name="direcciones[]"]');
        var checkboxDepartamentos = $('input[name="departamentos[]"]');

        this.start = function(){
            Codebase.helper('select2');

            var self = this;
            var selectUsuario = $('#usuario').on('change',function(){
                if ( this.value.length ){
                    
                    checkboxPermisos.prop('checked',false);
                    checkboxDirecciones.prop('checked',false);
                    checkboxDepartamentos.prop('checked',false);
                    
                    App.ajaxRequest({
                        url  : self.form.attr('action'),
                        data : { action : 1, usuario : this.value },
                        beforeSend : function(){
                            Codebase.blocks( self.context, 'state_loading');
                        },
                        success : function(result){
                            
                            $.each(result.permisos, function(index, permiso){
                                console.log(checkboxPermisos)
                                console.log(checkboxPermisos.filter('#permiso-'+permiso))
                                checkboxPermisos.filter('#permiso-'+permiso).prop('checked',true);
                            });

                            $.each(result.direcciones, function(index, direccion){
                                checkboxDirecciones.filter('#direccion-'+direccion).prop('checked',true);
                            });

                            $.each(result.departamentos, function(index, departamento){
                                checkboxDepartamentos.filter('#departamento-'+departamento).prop('checked',true);
                            });

                        },
                        complete : function(){
                            Codebase.blocks( self.context, 'state_normal');
                        }
                    });
                }
            });
            
        };

        this.rules = function(){
            return {
                usuario : { required : true }
            }
        };

        this.messages = function(){
            return {
                usuario : { required : 'Especifique un usuario' }
            }
        };

    }).init().start();
</script>
@endpush