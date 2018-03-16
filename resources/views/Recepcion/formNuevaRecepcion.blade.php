{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
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
            {!! Field::textarea('descripcion','',['label'=>'Asunto / Descripción','placeholder'=>'Introduzca una descripción del documento','size'=>'20x5','required','noresize']) !!}
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
                            <button type="button" class="btn btn-alt-primary" tabindex="-1" onclick="App.openModal({})">
                                <i class="fa fa-fw fa-plus"></i> Nuevo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {!! Field::textarea('anexos','',['label'=>'Anexos','placeholder'=>'Opcional','size'=>'20x11','noresize']) !!}
        </div>
    </div>
{{ Form::close() }}