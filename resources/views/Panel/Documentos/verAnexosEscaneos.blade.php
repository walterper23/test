@extends('vendor.templateModal',['headerColor'=>'bg-danger'])

@section('title')<i class="fa fa-fw fa-clipboard"></i> {!! $title !!}@endsection

@section('content')
    @component('vendor.contentModal')


    @endcomponent
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
	var formCambio = new AppForm;
	$.extend(formCambio, new function(){

		this.context_ = '#modal-ver-anexos-escaneos';

	}).init();
</script>
@endpush