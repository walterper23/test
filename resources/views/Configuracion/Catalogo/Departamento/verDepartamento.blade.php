@extends('vendor.modal.template_view')

@section('title')<i class="fa fa-fw fa-sitemap"></i> {!! $title !!}@endsection

@section('content')

	{!! Field::text('',$departamento -> getCodigo(),['label'=>'Código','disabled']) !!}
	{!! Field::text('',$departamento -> Direccion -> getNombre(),['label'=>'Direccion','disabled']) !!}
	{!! Field::text('',$departamento -> getNombre(),['label'=>'Nombre','disabled']) !!}
	{!! Field::text('',$departamento -> presenter() -> getFechaCreacion(),['label'=>'Fecha','disabled']) !!}
    
@endsection