{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
    <div class="row">
        <div class="col-md-6">
            {!! Field::select('tipo_documento','',['label'=>'Tipo de documento <span class="text-danger">*</span>','labelWidth'=>'col-md-4','width'=>'col-md-8'],$tipos_documentos) !!}
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-8">
                    {!! Field::text('no_oficio','',['label'=>'Nó. oficio','placeholder'=>'Número de oficio','labelWidth'=>'col-md-4','width'=>'col-md-8']) !!}
                </div>
                <div class="col-md-4">
                    {!! Field::text('anio',date('Y'),['label'=>'Año','placeholder'=>date('Y')]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            {!! Field::text('nombre_denunciante','',['label'=>'Razón social / Denunciante','labelWidth'=>'col-12','width'=>'col-12']) !!}
            {!! Field::text('representante','',['label'=>'Representante legal','labelWidth'=>'col-12','width'=>'col-12']) !!}
            {!! Field::textarea('direccion','',['label'=>'Dirección','labelWidth'=>'col-12','width'=>'col-12','size'=>'20x1']) !!}
            {!! Field::textarea('descripcion','',['label'=>'Descripción','placeholder'=>'Introduzca una descripción del documento','labelWidth'=>'col-12','width'=>'col-12','size'=>'20x4']) !!}
        </div>
        <div class="col-md-6">
            {!! Field::textarea('hechos','',['label'=>'Hechos de la denuncia','labelWidth'=>'col-12','width'=>'col-12','size'=>'20x5']) !!}
            <div class="form-group row">
                <label for="anexo" class="col-12 col-form-label">Seleccione un anexo</label>
                <div class="col-12">
                    <div class="input-group">
                        {{ Form::select('anexo',$anexos,null,['id'=>'anexo','class'=>'form-control','placeholder'=>'Seleccione una opción']) }}
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-alt-danger">
                                <i class="fa fa-fw fa-arrow-down"></i> Añadir
                            </button>
                            <button type="button" class="btn btn-alt-primary" tabindex="-1">
                                <i class="fa fa-fw fa-plus"></i> Nuevo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {!! Field::textarea('anexos','',['label'=>'Anexos','placeholder'=>'Opcional','labelWidth'=>'col-12','width'=>'col-12','size'=>'20x5']) !!}
            <div class="form-group row">
                
            </div>
            <div class="form-group row">
                    
            </div>
        </div>
    </div>
{{ Form::close() }}