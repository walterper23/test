{{ Form::open(['url'=>'recepcion/documentos/recepcionar','method'=>'POST','id'=>$form_id]) }}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="tipo_documento">Tipo de documento</label>
                {{ Form::select('tipo_documento',$tipos_documentos,null,['id'=>'tipo_documento','class'=>'form-control','placeholder'=>'Seleccione una opción']) }}
            </div>
            <div class="form-group">
                <label for="descripcion">Descripci&oacute;n</label>
                {{ Form::textarea('descripcion','',['id'=>'descripcion','class'=>'form-control','placeholder'=>'Introduzca una descripción del documento...']) }}
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
                        {{ Form::text('anio',date('Y'),['id'=>'anio','class'=>'form-control','placeholder'=>date('Y')]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Anexos</label>
                {{ Form::select('anexos',$anexos,null,['id'=>'anexo','class'=>'form-control','placeholder'=>'Seleccione una opción']) }}
            </div>
            <div class="form-group">
                
            </div>
            <div class="form-group">
                
            </div>
            <div class="form-group">
                
            </div>
        </div>
    </div>
{{ Form::close() }}