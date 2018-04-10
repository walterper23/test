{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id,'files'=>true]) }}
    {{ Form::hidden('action',1) }}
    <div class="row">
        <div class="col-md-7">
            <div class="form-group row">
                <label for="tipo_documento" class="col-md-3 col-form-label" required>Tipo de documento</label>
                <div class="col-md-9">
                    <select required id="tipo_documento" class="form-control" name="tipo_documento">
                        <option selected="selected" value="">Seleccione una opción</option>
                        @foreach( $tipos_documentos as $tipo )
                            {!! sprintf('<option value="%d" data-label="%s">%s</option>',$tipo -> getKey(), $tipo -> getEtiqueta(), $tipo -> getNombre() ) !!}
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            {!! Field::datepicker('recepcion',date('Y-m-d'),['label'=>'Recepción','required','placeholder'=>'yyyy-mm-dd']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            {!! Field::text('numero','',['label'=>'Nó.','required']) !!}
        </div>
        <div class="col-md-5">
            {!! Field::select('municipio',4,['label'=>'Municipio','required'],$municipios) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            {!! Field::textarea('descripcion','',['label'=>'Asunto / Descripción','placeholder'=>'Introduzca una descripción del documento','size'=>'20x3','required','noresize']) !!}
            {!! Field::text('responsable','',['label'=>'Responsable','required']) !!}
            {!! Field::textarea('observaciones','',['label'=>'Observaciones','placeholder'=>'Opcional','size'=>'20x4','noresize']) !!}
        </div>
        <div class="col-md-5">
            <div class="form-group row">
                <label for="anexo" class="col-md-3 col-form-label">Lista de anexos</label>
                <div class="col-md-9">
                    <div class="input-group">
                        {{ Form::select('anexo',$anexos,null,['id'=>'anexo','class'=>'form-control','placeholder'=>'Seleccione una opción']) }}
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-alt-danger" id="addAnexo">
                                <i class="fa fa-fw fa-arrow-down"></i>
                            </button>
                            <button type="button" class="btn btn-alt-primary" tabindex="-1" onclick="hRecepcion.new('form-anexo','{{ url('configuracion/catalogos/anexos/nuevo') }}')">
                                <i class="fa fa-fw fa-plus"></i> Nuevo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {!! Field::textarea('anexos','',['label'=>'Anexos','placeholder'=>'Opcional','size'=>'20x11','noresize']) !!}
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-9 ml-auto">
                    <button type="button" class="btn btn-danger btn-rounded btn-sm" data-toggle="modal" data-target="#modal-escaneos"><i class="fa fa-fw fa-file-pdf-o"></i> Escaneos <span class="badge badge-pill badge-secondary" id="conteo-escaneos"></span></button>
                    <button type="button" class="btn btn-success btn-rounded btn-sm" data-toggle="modal" data-target="#modal-entrega"><i class="fa fa-fw fa-vcard"></i> Quién entrega</span></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-escaneos" tabindex="-1" role="dialog" aria-labelledby="modal-escaneos" aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideright" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-pulse">
                        <h3 class="block-title"><i class="fa fa-fw fa-file-pdf-o"></i> Escaneos</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <ul>
                            <li>Agregue archivos <b>PDF</b> al archivo que esta recepcionando.</li>
                            <li>Cada archivo no debe ser mayor a <b>3 Mb</b>.</li>
                        </ul>
                        <div class="col-8">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="example-file-multiple-input-custom" name="example_file" multiple="">
                                <label class="custom-file-label" for="example-file-multiple-input-custom">Choose files</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-entrega" tabindex="-1" role="dialog" aria-labelledby="modal-entrega" aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideright" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-success">
                        <h3 class="block-title"><i class="fa fa-fw fa-vcard"></i> Quién entrega</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <p class="mb-10">Agregue información de contacto de quién entrega el documento. Esta información será reflejada opcionalmente en el Acuse de Recepción.</p>

                        {!! Field::text('nombre','',['label'=>'Nombre','placeholder'=>'Opcional']) !!}
                        {!! Field::text('telefono','',['label'=>'Teléfono','placeholder'=>'Opcional']) !!}
                        {!! Field::text('e-mail','',['label'=>'E-mail','placeholder'=>'Opcional']) !!}
                        {!! Field::text('identificacion','',['label'=>'Identificación','placeholder'=>'Opcional']) !!}

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>




{{ Form::close() }}