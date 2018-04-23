@extends('vendor.templateModal',['headerColor'=>'bg-danger'])

@section('title')<i class="fa fa-fw fa-flash"></i> {!! $title !!}@endsection

@section('content')
    @component('vendor.contentModal')
    {{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
        <input type="hidden" name="action" value="{{ $action }}"> 
        {{ Form::hidden('seguimiento',$seguimiento) }}

        <div class="col-12">
        	<div class="row">
	            <div class="col-md-4">
                    <div class="custom-control custom-radio custom-control-inline mb-5">
    	                <input class="custom-control-input" name="estado_documento" id="estado_radio_1" value="1" checked="" type="radio">
    	                <label class="custom-control-label" for="estado_radio_1">En seguimiento</label>
    	            </div>
                </div>
	            <div class="col-md-4">
                    <div class="custom-control custom-radio custom-control-inline mb-5">
    	                <input class="custom-control-input" name="estado_documento" id="estado_radio_2" value="2" type="radio">
    	                <label class="custom-control-label" for="estado_radio_2">Rechazar documento</label>
    	            </div>
                </div>
	            <div class="col-md-4">
                    <div class="custom-control custom-radio custom-control-inline mb-5">
    	                <input class="custom-control-input" name="estado_documento" id="estado_radio_3" value="3" type="radio">
    	                <label class="custom-control-label" for="estado_radio_3">Finalizar documento (resolver)</label>
    	            </div>
                </div>
            </div>
        </div>

        {!! Field::select('direccion_origen',null,['label'=>'Dirección origen','required','autofocus'],$direcciones_origen) !!}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="departamento_origen">Departamento origen</label>
            <div class="col-sm-9">
            	<select name="departamento_origen" id="departamento_origen" class="form-control">
					<option value="">Seleccione una opción</option>
            		@foreach( $departamentos_origen as $depto )
						{!! sprintf('<option data-direccion="%d" value="%d">%s</option>',$depto[0],$depto[1],$depto[2]) !!}
					@endforeach
					<option data-direccion value="0">- Ninguno -</option>
            	</select>
            </div>
        </div>
        {!! Field::select('direccion_destino',null,['label'=>'Dirección destino','autofocus'],$direcciones_destino) !!}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="departamento_destino">Departamento destino</label>
            <div class="col-sm-9">
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
        {!! Field::textarea('observacion','',['label'=>'Observaciones','size'=>'20x4','placeholder'=>'Opcional']) !!}
        {!! Field::textarea('instruccion','',['label'=>'Instrucción','size'=>'20x4','placeholder'=>'Instrucción al destino']) !!}
    {{ Form::close() }}
    @endcomponent
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
	var formCambio = new AppForm;
	$.extend(formCambio, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';	
        
        var radiosEstados = $('input[name="estado_documento"]');
		
		this.start = function(){

            var selectDireccionOrigen = $('#direccion_origen');
			var selectDepartamentoOrigen = $('#departamento_origen');
            var selectDireccionDestino = $('#direccion_destino');
            var selectDepartamentoDestino = $('#departamento_destino');
			var textAreaInstruccion = $('#instruccion');
			var optionsDeptoOrigen = selectDepartamentoOrigen.find('option[data-direccion]').hide();
			var optionsDeptoDestino = selectDepartamentoDestino.find('option[data-direccion]').hide();

            radiosEstados.on('change',function(e){
                selectDireccionDestino.add(selectDepartamentoDestino).add(textAreaInstruccion).closest('div.form-group.row').show()
                if ( radiosEstados.filter(':checked').val() != 1 ){
                    selectDireccionDestino.add(selectDepartamentoDestino).add(textAreaInstruccion).closest('div.form-group.row').hide()
                }
            });

			selectDireccionOrigen.on('change',function(){
				selectDepartamentoOrigen.val('');
				optionsDeptoOrigen.hide();
				if( this.value.length ){
					selectDepartamentoOrigen.find('option[value=0]').show();
					selectDepartamentoOrigen.find('option[data-direccion='+this.value+']').show();
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
				config.onClose = function(){
					location.reload();
				}
			}else{
				Codebase.blocks( formCambio.context.find('div.modal-content>div.block'), 'state_normal');
			}

			AppAlert.notify(config);
		};

		this.rules = function(){
			return {
				direccion_origen : { required : true },
				direccion_destino : {
					required : {
						depends : function(element){
							return radiosEstados.filter(':checked').val() == 1;
						}
					}
				},
				estado : { required : true }
			};
		};

		this.messages = function(){
			return {
				direccion_origen : { required : 'Especifique una dirección de origen' },
				direccion_destino : { required : 'Especifique una dirección de destino' },
				estado : { required : 'Especifique un estado de documento' }
			};
		};
	}).init().start();
</script>
@endpush