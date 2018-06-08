@extends('vendor.modal.template',['headerColor'=>'bg-danger'])

@section('title')<i class="fa fa-fw fa-flash"></i> {!! $title !!}@endsection

@section('content')

	
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
	var formCambio = new AppForm;
	$.extend(formCambio, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		
	}).init().start();
</script>
@endpush