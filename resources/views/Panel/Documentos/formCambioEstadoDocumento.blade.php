@extends('vendor.templateModal',['headerColor'=>'bg-danger'])

@section('title')<i class="fa fa-fw fa-flash"></i> {!! $title !!}@endsection

@section('content')
    @component('vendor.contentModal')
    {{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
        {{ Form::hidden('action',$action) }}    
        {{ Form::hidden('id',$id) }}
        {!! Field::select('direccion_origen',null,['label'=>'Dirección origen','autofocus'],$direcciones_origen) !!}
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
        {!! Field::select('estado','',['label'=>'Estado de Documento'],$estados) !!}
        {!! Field::textarea('observacion','',['label'=>'Observaciones','size'=>'20x5','placeholder'=>'Opcional']) !!}
    {{ Form::close() }}
    @endcomponent
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
	$.extend(new AppForm, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';	
		
		this.start = function(){

			var selectDepartamentoOrigen = $('#departamento_origen');
			var selectDepartamentoDestino = $('#departamento_destino');
			var optionsDeptoOrigen = selectDepartamentoOrigen.find('option[data-direccion]').hide();
			var optionsDeptoDestino = selectDepartamentoDestino.find('option[data-direccion]').hide();

			$('#direccion_origen').on('change',function(){
				selectDepartamentoOrigen.val('');
				optionsDeptoOrigen.hide();
				if( this.value.length ){
					selectDepartamentoOrigen.find('option[value=0]').show();
					selectDepartamentoOrigen.find('option[data-direccion='+this.value+']').show();
				}
			});

			$('#direccion_destino').on('change',function(){
				selectDepartamentoDestino.val('');
				optionsDeptoDestino.hide();
				if( this.value.length ){
					selectDepartamentoDestino.find('option[value=0]').show();
					selectDepartamentoDestino.find('option[data-direccion='+this.value+']').show();
				}
			});

		};

		this.rules = function(){
			return {
				nombre : { required : true, minlength : 3, maxlength : 255 },
				direccion : { required : true },
				departamento : { required : true },
				estado : { required : true }
			};
		};

		this.messages = function(){
			return {
				nombre : {
					required  : 'Introduzca un nombre',
					minlength : 'Introduzca mínimo {0} carácteres',
				},
				direccion : { required : 'Especifique una dirección' },
				departamento : { required : 'Especifique un departamento' },
				estado : { required : 'Especifique un estado de documento' }
			};
		};
	}).init().start();
</script>
@endpush