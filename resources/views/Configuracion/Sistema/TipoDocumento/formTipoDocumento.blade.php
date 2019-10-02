@extends('vendor.modal.template')

@section('title')<i class="fa fa-fw fa-files-o"></i> {!! $title !!} @endsection

@section('content')
{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
    {{ Form::hidden('action',$action) }}
    {{ Form::hidden('id',$id) }}
    {!! Field::text('nombre',optional($model)->getNombre(),['label'=>'Nombre','placeholder'=>'Nombre del tipo de documento','required','autofocus']) !!}
    {!! Field::text('etiqueta',optional($model)->getEtiqueta(),['label'=>'Solicitar','popover'=>['Solicitar','Introduzca la etiqueta a mostrar para solicitar el nó. de documento en el Módulo de Recepción. Si no indica nada, no se pedirá el nó. del documento en la recepción de documentos'],'placeholder'=>'Ej. Nó. oficio']) !!}
    {!! Field::text('codigo',optional($model)->getCodigoAcuse(),['label'=>'Código','popover'=>['Código para folio','Introduzca el código que será utilizado para los folios de las recepciones. Ej. \'DENU\' para generar folios PPACHE-'.date('Y').'-001174-DENU'],'placeholder'=>'Ej. DENU','required']) !!}
    {!! Field::select('color',optional($model)->getRibbonColor(),['label'=>'Color','placeholder'=>'Seleccione un color','required'],$colores) !!}
    <div class="form-group row">
        <div class="col-md-9 ml-auto font-size-md text-center">
            <span id="badge-nombre" class="badge"></span>
        </div>
    </div>
{{ Form::close() }}
@endsection

@push('js-custom')
<script type="text/javascript">
    'use strict';
    $.extend(new AppForm, new function(){

        this.context_ = '#modal-{{ $form_id }}';
        this.form_    = '#{{ $form_id }}';

        this.start = function(){

            Codebase.helper('core-popover');
            
            $('#nombre').on('keyup', (e) => {
                this.setBadge( e.target.value );
            });

            $('#color').on('change',function(e){
                let classBadge = 'badge badge-' + this.value;
                $('#badge-nombre').attr('class',classBadge);
            });

            $('#nombre').keyup();
            $('#color').change();
        };

        this.setBadge = function( value ){
            $('#badge-nombre').text(value);
        };
            
        this.rules = function(){
            return {
                nombre : { required : true, maxlength : 100 },
                etiqueta : { maxlength : 100 },
                codigo : { required : true },
                color : { required : true }
            }
        };

        this.messages = function(){
            return {
                nombre : {
                    required : 'Introduzca un nombre',
                    maxlength : 'Máximo {0} caracteres'
                },
                etiqueta : {
                    maxlength : 'Máximo {0} caracteres'
                },
                codigo : {
                    required : 'Introduzca un código para los folios',
                },
                color : {
                    required : 'Seleccione un color',
                }
            };
        };

    }).init().start();
</script>
@endpush