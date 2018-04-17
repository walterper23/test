@extends('vendor.templateModal')

@section('title')<i class="fa fa-flash"></i> {!! $title !!}@endsection

@push('css-style')
    {{ Html::style('js/plugins/select2/select2.min.css') }}
    {{ Html::style('js/plugins/select2/select2-bootstrap.min.css') }}
@endpush

@section('content')
	@component('vendor.contentModal')
    {!! Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) !!}
	    {!! Form::hidden('action',$action) !!}
	    {!! Form::hidden('id',$id) !!}
        {!! Field::select('direccion',(optional($modelo) -> ESDO_DIRECCION),['label'=>'Dirección','required'],$direcciones) !!}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="departamento" required>Departamento</label>
            <div class="col-sm-9">
            	<select name="departamento" id="departamento" class="form-control">
					<option value="">Seleccione una opción</option>
            		@foreach( $departamentos as $depto )
						{!! sprintf('<option data-direccion="%d" value="%d">%s</option>',$depto[0],$depto[1],$depto[2]) !!}					
					@endforeach
					<option data-direccion value="0">- Ninguno -</option>
            	</select>
            </div>
        </div>
        {!! Field::text('nombre',(optional($modelo) -> ESDO_NOMBRE),['label'=>'Nombre','placeholder'=>'Nombre del estado de documento','required','maxlength'=>150]) !!}
	{!! Form::close() !!}
	@endcomponent
@endsection

@push('js-custom')

{{ Html::script('js/plugins/select2/select2.full.min.js') }}

<script type="text/javascript">
	'use strict';
	var formEstadoDocumento = new AppForm;
	$.extend(formEstadoDocumento, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{$form_id}}';

		this.start = function(){

			Codebase.helper('select2');

			var selectDireccion = $('#direccion');
			var selectDepartamento = $('#departamento');
			var options = selectDepartamento.find('option[data-direccion]').hide();

			selectDireccion.on('change',function(){
				selectDepartamento.val('');
				options.hide();
				if( this.value.length ){
					selectDepartamento.find('option[value=0]').show();
					selectDepartamento.find('option[data-direccion='+this.value+']').show();
				}
			});

			if( selectDireccion.find('option').length == 1 ){
				selectDireccion.find('option:first').attr('selected','selected').trigger('change');
			}

		};
	
		this.rules = function(){
			return {
				direccion : { required : true },
				departamento : { required : true },
				nombre : { required : true, maxlength : 150 }
			}
		}

		this.messages = function(){
			return {
				direccion : { required : 'Especifique una dirección' },
				departamento : { required : 'Especifique un departamento' },
				nombre : {
					required : 'Introduzca un nombre',
					maxlength : 'Máximo {0} caracteres'
				}
			}
		}
	}).init().start();
</script>
@endpush