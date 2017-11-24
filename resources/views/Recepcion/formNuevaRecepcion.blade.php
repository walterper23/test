{{ Form::open(['url'=>'','method'=>'POST']) }}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="tipo_documento">Tipo de documento</label>
                {{ Form::select('tipo_documento',$tipos_documentos,null,['id'=>'tipo_documento','class'=>'form-control','placeholder'=>'Seleccione una opción']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="no_oficio">N&oacute;. oficio</label>
                        <input type="text" class="form-control" id="no_oficio" name="no_oficio" placeholder="Número de oficio">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="anio">A&ntilde;o</label>
                        {{ Form::text('anio',date('Y'),['id'=>'anio','class'=>'form-control']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripci&oacute;n</label>
                {{ Form::textarea('descripcion','',['id'=>'descripcion','class'=>'form-control','placeholder'=>'Introduzca una descripción del documento...']) }}
            </div>
            <div class="form-group">
                <label for="">Anexos</label>
                <div id="anexos">

                </div>
            </div>
            <div class="form-group">
                
            </div>
            <div class="form-group">
                
            </div>
            <div class="form-group">
                
            </div>
        </div>
    </div>
    <div class="form-group text-right">
        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Validar</button>
        <button type="submit" class="btn btn-info"><i class="fa fa-disk-floppy"></i> Guardar</button>
        <button type="submit" class="btn btn-primary ml-20">Recepcionar</button>
    </div>
{{ Form::close() }}