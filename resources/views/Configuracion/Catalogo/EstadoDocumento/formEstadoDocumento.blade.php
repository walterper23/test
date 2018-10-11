@extends('vendor.modal.template')

@section('title')<i class="fa fa-fw fa-flash"></i> {!! $title !!}@endsection

@section('content')
{!! Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) !!}
    {!! Form::hidden('action',$action) !!}
    {!! Form::hidden('id',$id) !!}
    {!! Field::select('direccion',(optional($modelo) -> ESDO_DIRECCION),['label'=>'Dirección','required'],$direcciones) !!}
    <div class="form-group row">
        <label class="col-md-3 col-form-label" for="departamento" required>Departamento</label>
        <div class="col-md-9">
        	<select name="departamento" id="departamento" class="form-control">
				@if(sizeof($direcciones) > 0)
					<option value="">Seleccione una opción</option>
	        		@foreach( $departamentos as $depto )
						{!! sprintf('<option data-direccion="%d" value="%d">%s</option>',$depto[0],$depto[1],$depto[2]) !!}					
					@endforeach
					<option data-direccion value="0">- Ninguno -</option>
				@endif
        	</select>
        </div>
    </div>
    {!! Field::text('nombre',(optional($modelo) -> ESDO_NOMBRE),['label'=>'Nombre','placeholder'=>'Nombre del estado de documento','required','maxlength'=>150]) !!}
{!! Form::close() !!}
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
	var formEstadoDocumento = new AppForm;
	$.extend(formEstadoDocumento, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{$form_id}}';

		this.start = function(){

			var selectDireccion = $('#direccion');
			var selectDepartamento = $('#departamento');
			var options = selectDepartamento.find('option[data-direccion]').hide();

			selectDireccion.on('change',function(){
				selectDepartamento.val('');
				options.hide();
				if( this.value.length ){
					var departamentosDireccion = selectDepartamento.find('option[data-direccion='+this.value+']');
					var departamentoNinguno = selectDepartamento.find('option[value=0]').first();
					departamentoNinguno.show();
					if ( departamentosDireccion.length ){
						departamentosDireccion.show();
						if( departamentosDireccion.length == 1 ){
							selectDepartamento.val( departamentosDireccion.first().val() );
						}
					}else{
						selectDepartamento.val( departamentoNinguno.val() );
					}
				}
			});

			if( selectDireccion.find('option').length == 1 )
				selectDireccion.change()
			
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