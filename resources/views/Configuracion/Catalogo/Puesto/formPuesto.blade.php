@extends('vendor.templateModal')

@section('title')<i class="fa fa-fw fa-users"></i> {!! $title !!}@endsection

@section('content')
	@component('vendor.contentModal')
	{{ Form::model($modelo,['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
		{{ Form::hidden('action',$action) }}	
		{{ Form::hidden('id',$id) }}
		{!! Field::select('direccion',(is_null($modelo) ? null : $modelo->PUES_DIRECCION),['label'=>'Dirección'],$direcciones) !!}
		<div class="form-group row">
            <label class="col-sm-3 col-form-label" for="departamento">Departamento</label>
            <div class="col-sm-9">
            	<select name="departamento" id="departamento" class="form-control" placeholder="Seleccione una opción">
					<option value="">Seleccione una opción</option>
            		@foreach( $departamentos as $depto )
						{!! sprintf('<option data-direccion="%d" value="%d">%s</option>',$depto[0],$depto[1],$depto[2]) !!}					
					@endforeach
					<option data-direccion value="0">- Ninguno -</option>
            	</select>
            </div>
        </div>

		<div class="form-group row">
			<label class="col-sm-3 col-form-label" for="nombre">Nombre</label>
			<div class="col-sm-9">
				{{ Form::text('nombre',(is_null($modelo) ? '' : $modelo->PUES_NOMBRE),['id'=>'nombre','class'=>'form-control','placeholder'=>'Nombre del puesto','autofocus']) }}
            </div>
        </div>
	{{ Form::close() }}
	@endcomponent
@endsection

@push('js-custom')
<script type="text/javascript">
	
	$.extend(AppForm, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';	
		
		this.start = function(){

			var selectDepartamento = $('#departamento');
			var options = selectDepartamento.find('option[data-direccion]').hide();

			$('#direccion').on('change',function(){
				selectDepartamento.val('');
				options.hide();
				if( this.value.length ){
					selectDepartamento.find('option[value=0]').show();
					selectDepartamento.find('option[data-direccion='+this.value+']').show();
				}
			});

		};

		this.rules = function(){
			return {
				departamento : { required : true },
				nombre : { required : true, minlength : 3, maxlength : 255 },
			}
		};

		this.messages = function(){
			return {
				direccion : { required : 'Especifique una direccion' },
				departamento : { required : 'Especifique un departamento' },
				nombre : {
					required  : 'Introduzca un nombre',
					minlength : 'Mínimo {0} caracteres',
					maxlength : 'Máximo {0} caracteres',
				}
			}
		};

	}).init().start();

</script>
@endpush