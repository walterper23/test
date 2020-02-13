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
                    <div class="row">
                        <div class="alert alert-info" role="alert">
                            <p class="mt-0 mb-0">A continuación se enlistan las direcciones y sus departamentos. Seleccione las direcciones y/o departamentos a las que desea enviar el documento.</p>
                        </div>
                    </div>
                    <div class="row">
                        {{ Form::open(['id'=>'tree-direcciones-departamentos']) }}
                            @foreach( $direcciones as $direccion )
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-12">
                                        <div class="custom-control custom-checkbox mb-5">
                                            <input class="custom-control-input" type="checkbox" name="direcciones[]" id="direcciones_{{ $direccion->getKey() }}" value="{{ $direccion->getKey() }}">
                                            <label class="custom-control-label font-w700 text-primary pl-5 pr-5" for="direcciones_{{ $direccion->getKey() }}" style="width: 100%;">{{ $direccion->getNombre() }}</label>
                                        </div>
                                        @forelse( $direccion->DepartamentosExistentesDisponibles as $departamento )
                                        <div class="custom-control custom-checkbox mb-5 ml-30">
                                            <input class="custom-control-input" type="checkbox" name="departamentos[]" id="departamento_{{ $departamento->getKey() }}" value="{{ $departamento->getKey() }}">
                                            <label class="custom-control-label" for="departamento_{{ $departamento->getKey() }}">{{ $departamento->getNombre() }}</label>
                                        </div>
                                        @empty
                                        <p class="font-size-xs text-muted pl-30">- No hay departamentos -</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal-popout">Cerrar</button>
            </div>
        </div>
    </div>
</div>