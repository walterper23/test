@extends('vendor.modal.template')

@section('title')<i class="fa fa-fw fa-files-o"></i> {!! $title !!} @endsection

@section('content')
{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) }}
    {{ Form::hidden('action',$action) }}
    {{ Form::hidden('id',$id) }}
    {!! Field::text('nombre',optional($model) -> getNombre(),['label'=>'Nombre','placeholder'=>'Nombre del tipo de documento','required','autofocus']) !!}
    {!! Field::text('etiqueta',optional($model) -> getEtiqueta(),['label'=>'Solicitar','placeholder'=>'Ej. Nó. oficio']) !!}
    {!! Field::select('color',optional($model) -> getRibbonColor(),['label'=>'Color','placeholder'=>'Seleccione un color','required'],$colores) !!}
    <div class="form-group row">
        <div class="col-md-9 ml-auto font-size-md">
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
                color : {
                    required : 'Seleccione un color',
                }
            };
        };

    }).init().start();
</script>
@endpush