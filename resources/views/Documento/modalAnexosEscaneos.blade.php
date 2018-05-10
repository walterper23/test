@extends('vendor.modal.template',['headerColor'=>'bg-pulse'])

@section('title')
	<i class="fa fa-fw fa-clipboard"></i> {!! $title !!}
@endsection

@section('content')
<div class="col-12">
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