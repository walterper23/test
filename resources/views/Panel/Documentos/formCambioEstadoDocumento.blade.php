@extends('vendor.modal.template',['headerColor'=>'bg-danger'])

@section('title')<i class="fa fa-fw fa-flash"></i> {!! $title !!}@endsection

@section('content')
{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
    {{ Form::hidden('action',$action) }}
    {{ Form::hidden('documento',$documento) }}
    
    @isset($contestar)
    <div class="form-group row">
        <div class="col-md-12">
        </span> <span class="font-w600">Conteste a la solicitud realizada por el origen <span class="text-primary font-w700">{{ $origen_solicitud }}</span>.</span>
            <textarea placeholder="Respuesta a la solicitud realizada por el origen" noresize="" id="contestacion" class="form-control" name="contestacion" cols="20" rows="2"></textarea>
        </div>
    </div>
    <hr>
    @endisset

    {!! Field::select('estado_documento',1,['label'=>'Seguimiento','placeholder'=>false,'required'],$system_estados_documentos) !!}
    {!! Field::select('dispersion',1,['label'=>'Dispersión','placeholder'=>false,'required'],$dispersiones) !!}
    {!! Field::text('direccion_origen',$direccion_origen,['label'=>'Dirección origen','disabled']) !!}
    {!! Field::text('departamento_origen',$departamento_origen,['label'=>'Departamento origen','disabled']) !!}
    {!! Field::select('direccion_destino',null,['label'=>'Dirección destino','autofocus','required'],$direcciones_destino) !!}
    <div class="form-group row">
        <label class="col-md-3 col-form-label" for="departamento_destino" required>Departamento destino</label>
        <div class="col-md-9">
            <select name="departamento_destino" id="departamento_destino" class="form-control">
                <option value="">Seleccione una opción</option>
                @foreach( $departamentos_destino as $depto )
                    {!! sprintf('<option data-direccion="%d" value="%d">%s</option>',$depto[0],$depto[1],$depto[2]) !!}
                @endforeach
                <option data-direccion value="0">- Ninguno -</option>
            </select>
        </div>
    </div>
    <div class="form-group row" id="form-group-dispersion" style="display: none;">
        <label class="col-md-3 col-form-label" for="areas_dispersion" required>Áreas de destino</label>
        <div class="col-md-9">
            <button type="button" id="areas_dispersion" class="btn btn-primary" data-toggle="modal" data-target="#modal-popout">
                <i class="fa fa-fw fa-sitemap"></i> Elegir direcciones y departamentos
            </button>
            <!--button type="button" id="areas_dispersion" class="btn btn-primary" onclick="hPanel.nuevoEstado()">
                <i class="fa fa-fw fa-sitemap"></i> Elegir direcciones y departamentos
            </button-->
            <div class="modal fade" id="modal-popout" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true" style="z-index: 999999">
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
                                @include('Panel.Seguimiento.direccionesDepartamentos',[$direcciones])
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
</div>
        </div>
    </div>
    {!! Field::select('estado','',['label'=>'Estado de Documento','required'],$estados) !!}
    {!! Field::textarea('observacion','',['label'=>'Observaciones','size'=>'20x2','placeholder'=>'Opcional','noresize']) !!}
    <hr>
    {!! Field::textarea('instruccion','',['label'=>'Instrucción al destino','size'=>'20x2','placeholder'=>'Opcional','noresize']) !!}
    @can('SEG.ADMIN.SEMAFORO')
    <div class="form-group row">
        <label class="col-md-3 col-form-label" for="semaforizar">Semaforizar</label>
        <div class="col-md-9">
            <label class="css-control css-control-primary css-checkbox disabled {{ isset($contestar) ? '' : 'd-none' }}" id="label-no-semaforizar">
                <input class="css-control-input" type="checkbox" disabled="">
                <span class="css-control-indicator"></span> <i class="fa fa-fw fa-warning"></i> No se puede semaforizar. Primero debe contestar a la solicitud realizada por el origen <b>{{ $origen_solicitud }}</b>.
            </label>
            <label class="css-control css-control-primary css-checkbox {{ isset($contestar) ? 'd-none' : '' }}" id="label-semaforizar">
                <input class="css-control-input" name="semaforizar" id="semaforizar" value="1" type="checkbox">
                <span class="css-control-indicator"></span> <i class="fa fa-fw fa-flag"></i> Solicitar al destino que conteste a este Cambio de Estado.
            </label>
        </div>
    </div>
</div>
    @endcan
{{ Form::close() }}
@endsection

@push('js-custom')
<script type="text/javascript">
    'use strict';
    var formCambio = new AppForm;
    $.extend(formCambio, new function(){

        this.context_ = '#modal-{{ $form_id }}';
        this.form_    = '#{{ $form_id }}';  
        
        var selectDispersion  = $('#dispersion');
        var selectSeguimiento = $('#estado_documento');
        
        this.start = function(){

            this.form.validate({
                ignore: []
            });

            var formGroupDispersion = $('#form-group-dispersion');
            var selectDireccionDestino = $('#direccion_destino');
            var selectDepartamentoDestino = $('#departamento_destino');
            var textAreaInstruccion = $('#instruccion');
            var checkSemaforizar = $('#semaforizar');
            var optionsDeptoDestino = selectDepartamentoDestino.find('option[data-direccion]').hide();

            selectSeguimiento.on('change',function(e){
                selectDireccionDestino.add(selectDepartamentoDestino).add(textAreaInstruccion).add(checkSemaforizar).closest('div.form-group.row').show()
                selectDispersion.find('option[value=2]').hide();
                if ( this.value != 1 ){
                    selectDireccionDestino.add(selectDepartamentoDestino).add(textAreaInstruccion).add(checkSemaforizar).closest('div.form-group.row').hide();
                }

                if ( this.value == 3 ){
                    selectDispersion.find('option[value=2]').show();
                }else{
                    selectDispersion.val(1);
                }
                selectDispersion.change();
            });
            
            selectDispersion.on('change',function(e){
                formGroupDispersion.hide();
                var direccion    = selectDireccionDestino.closest('.form-group').hide();
                var departamento = selectDepartamentoDestino.closest('.form-group').hide();
                if( this.value == 1 && selectSeguimiento.val() == 1){ // Dispersión normal y documento en seguimiento
                    direccion.add(departamento).fadeIn(750)
                }else if( this.value == 2 ){ // Dispersión múltiple
                    selectSeguimiento.val(3);
                    formGroupDispersion.fadeIn(750);
                }
            });

            selectDireccionDestino.on('change',function(){
                selectDepartamentoDestino.val('');
                optionsDeptoDestino.hide();
                if( this.value.length ){
                    selectDepartamentoDestino.find('option[value=0]').show();

                    var optionsDestino = selectDepartamentoDestino.find('option[data-direccion='+this.value+']');
                    if ( optionsDestino.length > 0 )
                        selectDepartamentoDestino.find('option[data-direccion='+this.value+']').show();
                    else
                        selectDepartamentoDestino.val(0);
                }
            });

            $('#contestacion').on('keyup',function(){
                $('#label-no-semaforizar').add('#label-semaforizar').addClass('d-none');
                if( this.value.trim().length ){
                    $('#label-semaforizar').removeClass('d-none');
                }else{
                    $('#label-no-semaforizar').removeClass('d-none');
                }
            });

            $('#modal-popout').on('shown.bs.modal', function() {
               //To relate the z-index make sure backdrop and modal are siblings
               // $(this).before($('.modal-backdrop'));
               //Now set z-index of modal greater than backdrop
               // $(this).css("z-index", parseInt($('.modal-backdrop').css('z-index')) + 1);
               $(this).css("z-index", parseInt(1051));
            }); 

        };

        this.successSubmitHandler = function( result ){
            var config = {
                type: result.type,
                message : result.message,
                z_index : 9999
            };

            if( result.status ){
                config.onShown = function(){
                    location.reload();
                }
            }else{
                Codebase.blocks( formCambio.context.find('div.modal-content>div.block'), 'state_normal');
            }

            AppAlert.notify(config);
        };

        this.rules = function(){
            return {
                contestacion : {
                    required : true,
                    minlength : 1
                },
                estado_documento : { required : true },
                direcciones : {
                    required : true
                },
                departamentos : {
                    required : true
                },
                direccion_destino : {
                    required : {
                        depends : function(element){
                            return selectSeguimiento.val() == 1;
                        }
                    }
                },
                departamento_destino : {
                    required : {
                        depends : function(element){
                            return selectSeguimiento.val() == 1;
                        }
                    }
                },
                estado : { required : true }
            };
        };

        this.messages = function(){
            return {
                contestacion : {
                    required : 'Introduzca la respuesta a la solicitud',
                    minlength : 'Mínimo {0} caracteres'
                },
                estado_documento : { required : 'Especifique el seguimiento del documento' },
                direccion_destino : { required : 'Especifique una dirección de destino' },
                departamento_destino : { required : 'Especifique un departamento de destino' },
                estado : { required : 'Especifique un estado de documento' },
            };
        };
    }).init().start();
</script>
@endpush