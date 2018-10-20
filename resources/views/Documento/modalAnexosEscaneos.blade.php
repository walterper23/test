@extends('vendor.modal.template',['headerColor'=>'bg-pulse'])

@section('title')
	<i class="fa fa-fw fa-clipboard"></i> {!! $title !!}
@endsection

@section('content')
<div class="col-12">

	<div class="row">
		<div class="col-md-12 text-center">
			<p class="font-size-md"><span class="text-danger"><b>Folio de Recepción:</b></span> {{ $folio_recepcion }}</p>
		</div>
	</div>

	<div class="row">
		{!! $anexos or '' !!}
		{!! $escaneos or '' !!}
	</div>
</div>
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';

	var modalAnexoEscaneo = new AppForm;
	$.extend(modalAnexoEscaneo, new function(){
		this.context_ = '#modal-anexos-escaneos';
	}).init();
</script>
@endpush