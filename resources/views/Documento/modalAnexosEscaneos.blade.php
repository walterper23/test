@extends('vendor.templateModal',['headerColor'=>'bg-pulse'])

@section('title')<i class="fa fa-fw fa-clipboard"></i> {!! $title !!}@endsection

@section('content')
	@component('vendor.contentModal')
    	<div class="col-12">
    		<div class="row">
				{!! $anexos or '' !!}
				{!! $escaneos or '' !!}
			</div>
		</div>
	@endcomponent
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