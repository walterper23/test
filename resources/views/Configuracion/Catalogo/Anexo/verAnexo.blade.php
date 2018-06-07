@extends('vendor.modal.template_view')

@section('title')<i class="fa fa-fw fa-clipboard"></i> {!! $title !!}@endsection

@section('content')

	{!! Field::text('',$anexo -> getCodigo(),['label'=>'Código','disabled']) !!}
	{!! Field::text('',$anexo -> getNombre(),['label'=>'Nombre','disabled']) !!}
	{!! Field::text('',$anexo -> presenter() -> getFechaCreacion(),['label'=>'Fecha','disabled']) !!}
    
@endsection