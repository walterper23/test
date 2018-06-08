@extends('vendor.modal.template',['headerColor'=>'bg-danger'])

@section('title')<i class="fa fa-fw fa-flash"></i> {!! $title !!}@endsection

@section('content')
{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
    {{ Form::hidden('action',$action) }}
    {{ Form::hidden('documento',$documento) }}
	
	@isset($contestar)
	<div class="form-group row">
        <div class="col-md-3">
        	<label class="css-control css-control-primary css-checkbox">
                <input class="css-control-input" name="contestar" id="contestar" value="1" type="checkbox">
                <span class="css-control-indicator"></span> <span class="font-w600">Contestar la solicitud realizada por el origen</span>
            </label>
        </div>
        <div class="col-md-9">
        	<textarea placeholder="Respuesta a la solicitud realizada por el origen" noresize="" id="contestacion" class="form-control" name="contestacion" cols="20" rows="2"></textarea>
        </div>
    </div>
	<hr>
	@endisset

	{!! Field::select('estado_documento',1,['label'=>'Seguimiento','required'],[1=>'En seguimiento',2=>'Rechazar documento',3=>'Finalizar documento (resolver)']) !!}
    {!! Field::text('direccion_origen',$direccion_origen,['label'=>'Dirección origen','disabled']) !!}
    {!! Field::text('departamento_origen',$departamento_origen,['label'=>'Departamento origen','disabled']) !!}
    {!! Field::select('direccion_destino',null,['label'=>'Dirección destino','autofocus','required'],$direcciones_destino) !!}
    <div class="form-group row">
        <label class="col-md-3 col-form-label" for="departamento_destino">Departamento destino</label>
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
    {!! Field::select('estado','',['label'=>'Estado de Documento','required'],$estados) !!}
    {!! Field::textarea('observacion','',['label'=>'Observaciones','size'=>'20x2','placeholder'=>'Opcional','noresize']) !!}
    <hr>
    {!! Field::textarea('instruccion','',['label'=>'Instrucción al destino','size'=>'20x2','placeholder'=>'Opcional','noresize']) !!}
    @can('SEG.ADMIN.SEMAFORO')
    <div class="form-group row">
    	<label class="col-md-3 col-form-label" for="semaforizar">Semaforizar</label>
        <div class="col-md-9">
        	@isset($contestar)
        	<label class="css-control css-control-primary css-checkbox disabled">
                <input class="css-control-input" type="checkbox" disabled="">
                <span class="css-control-indicator"></span> <i class="fa fa-fw fa-warning"></i> No se puede semaforizar. En espera de respuesta a la última solicitud realizada. 
            </label>
            @else
			<label class="css-control css-control-primary css-checkbox">
                <input class="css-control-input" name="semaforizar" id="semaforizar" value="1" type="checkbox">
                <span class="css-control-indicator"></span> <i class="fa fa-fw fa-flag"></i> Solicitar al destino, contestar a este Cambio de Estado
            </label>
            @endisset
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
        
        var selectSeguimiento = $('#estado_documento');
		
		this.start = function(){

            var selectDireccionDestino = $('#direccion_destino');
            var selectDepartamentoDestino = $('#departamento_destino');
			var textAreaInstruccion = $('#instruccion');
			var checkSemaforizar = $('#semaforizar');
			var optionsDeptoDestino = selectDepartamentoDestino.find('option[data-direccion]').hide();

            selectSeguimiento.on('change',function(e){
                selectDireccionDestino.add(selectDepartamentoDestino).add(textAreaInstruccion).add(checkSemaforizar).closest('div.form-group.row').show()
                if ( selectSeguimiento.val() != 1 ){
                    selectDireccionDestino.add(selectDepartamentoDestino).add(textAreaInstruccion).add(checkSemaforizar).closest('div.form-group.row').hide()
                }
            });

			selectDireccionDestino.on('change',function(){
				selectDepartamentoDestino.val('');
				optionsDeptoDestino.hide();
				if( this.value.length ){
					selectDepartamentoDestino.find('option[value=0]').show();
					selectDepartamentoDestino.find('option[data-direccion='+this.value+']').show();
				}
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
					required : {
						depends : function(element){
							return $('#contestar').is(':checked');
						}
					},
					minlenght : 1
				},
				estado_documento : { required : true },
				direccion_origen : { required : true },
				direccion_destino : {
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
					minlenght : 'Mínimo {0} caracteres'
				},
				estado_documento : { required : 'Especifique el seguimiento del documento' },
				direccion_origen : { required : 'Especifique una dirección de origen' },
				direccion_destino : { required : 'Especifique una dirección de destino' },
				estado : { required : 'Especifique un estado de documento' }
			};
		};
	}).init().start();
</script>
@endpush