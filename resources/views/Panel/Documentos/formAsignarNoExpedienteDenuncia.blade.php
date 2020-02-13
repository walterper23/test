@extends('vendor.modal.template',['headerColor'=>'bg-danger'])

@section('title')<i class="fa fa-fw fa-legal"></i> {!! $title !!}@endsection

@section('content')
{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
    {{ Form::hidden('action',$action) }}
    {{ Form::hidden('id',$id) }}
    {!! Field::text('expediente',$no_expediente,['label'=>'Nó. expediente','required','labelWidth'=>'col-md-4','width'=>'col-md-8','autofocus']) !!}
{{ Form::close() }}
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
	var formNoExpediente = new AppForm;
	$.extend(formNoExpediente, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';	
        
		this.start = function(){

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
				Codebase.blocks( formNoExpediente.context.find('div.modal-content>div.block'), 'state_normal');
			}

			AppAlert.notify(config);
		};

		this.rules = function(){
			return {
				expediente : { required : true, minlength : 1, maxlength : 255 },
			};
		};

		this.messages = function(){
			return {
				expediente : {
					required : 'Introduzca el número de expediente de la denuncia',
					minlength : 'Mínimo {0} caracteres',
					maxlength : 'Máximo {0} caracteres'
				}
			};
		};
	}).init().start();
</script>
@endpush