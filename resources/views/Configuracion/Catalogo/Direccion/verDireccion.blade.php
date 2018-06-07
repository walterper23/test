@extends('vendor.modal.template_view')

@section('title')<i class="fa fa-fw fa-sitemap"></i> {!! $title !!}@endsection

@section('content')

	{!! Field::text('',$direccion -> getCodigo(),['label'=>'Código','disabled']) !!}
	{!! Field::text('',$direccion -> getNombre(),['label'=>'Nombre','disabled']) !!}
	{!! Field::text('',$direccion -> Departamentos -> count(),['label'=>'Departamentos','disabled']) !!}
	{!! Field::text('',$direccion -> presenter() -> getFechaCreacion(),['label'=>'Fecha','disabled']) !!}
    
@endsection