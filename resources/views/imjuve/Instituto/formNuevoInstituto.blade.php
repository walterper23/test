@extends('vendor.modal.template',['headerColor'=>'bg-earth'])

@section('title')<i class="fa fa-fw fa-user-plus"></i> {!! $title !!}@endsection

@section('content')
{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
    {{ Form::hidden('action',$action) }}
    <div class="row">
    <div class="col-md-6">
        {!! Field::text('organismo','',['label'=>'Nombre del organismo','autofocus']) !!}
        {!! Field::text('razon','',['label'=>'Razon social','placeholder'=>'Opcional','maxlength'=>10]) !!}
        {!! Field::text('telefono','',['label'=>'Telefono','placeholder'=>'Ej. +52 9831234567','maxlength'=>255]) !!}
      <!--  {!! Field::text('calle','',['label'=>'Calle(s)','maxlength'=>255]) !!}
        {!! Field::text('noext','',['label'=>'Numero exterior','maxlength'=>255]) !!}
        {!! Field::text('noint','',['label'=>'Numero interior','maxlength'=>255]) !!} -->

    </div>

    <div class="col-md-6">
       <!--   {!! Field::text('codigo_postal','',['label'=>'Codigo Postal','maxlength'=>255]) !!}
        {!! Field::select('Asentamiento','',['label'=>'Tipo de Asentamiento'],['Urbano'=>'Urbano','Rural'=>'Rural']) !!}
        {!! Field::select('estado','',['label'=>'Estado'],['Quintana Roo'=>'Quintana Roo','Yucatan'=>'Yucatan']) !!}
        {!! Field::select('municipio','',['label'=>'Municipio'],['Othon P. Blanco'=>'Othon P. Blanco','Benito Juarez'=>'Benito Juarez']) !!}
        {!! Field::select('localidad','',['label'=>'Localidad'],['Allende'=>'Allende','Limones'=>'Limones','Huay-Pix'=>'Huay-Pix','NicolasBravo'=>'Nicolas Bravo']) !!}
        {!! Field::select('vialidad','',['label'=>'Tipo de Vialidad'],['ampliación'=>'Ampliación','avenida'=>'Avenida','privada'=>'Privada','retorno'=>'Retorno','andador'=>'Andador']) !!}
 -->
    </div>
    
    </div>
{{ Form::close() }}
@endsection

@push('js-custom')
<script type="text/javascript">
	'use strict';
    var formUser = new AppForm;
	$.extend(formUser, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{ $form_id }}';

        this.start = function(){

            var self = this;

            // Prevent forms from submitting on enter key press
            self.form.on('keyup keypress', function (e) {
                var code = e.keyCode || e.which;

                if (code === 13) {
                    e.preventDefault();
                    return false;
                }
            });

        };
	
	

	

        this.displayError = function( index, value ){
            AppAlert.notify({
                type    : 'warning',
                icon    : 'fa fa-fw fa-warning',
                message : value[0],
                z_index : 9999
            });
        };

	}).init().start();

</script>
@endpush