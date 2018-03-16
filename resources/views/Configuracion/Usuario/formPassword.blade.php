@extends('vendor.templateModal')

@section('title')<i class="fa fa-fw fa-key"></i> {!! $title !!}@endsection

@section('content')
	@component('vendor.contentModal')
    {!! Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id]) !!}
	    {!! Form::hidden('action',$action) !!}
	    {!! Form::hidden('id',$id) !!}
        {!! Field::email('usuario',$usuario,['label'=>'Usuario','addClass'=>'text-lowercase','disabled'=>'disabled']) !!}
	    {!! Field::password('password','',['label'=>'Contraseña','required']) !!}
	    {!! Field::password('password_confirmation','',['label'=>'Confirmar contraseña','required']) !!}
	{!! Form::close() !!}
	@endcomponent
@endsection

@push('js-custom')
<script type="text/javascript">
	
	$.extend(AppForm, new function(){

		this.context_ = '#modal-{{ $form_id }}';
		this.form_    = '#{{$form_id}}';
	
		this.rules = function(){
			return {
				password : { required : true, minlength: 6, maxlength : 20 },
                password_confirmation : { required : true, minlength: 6, maxlength : 20, equalTo : '#password' },
			}
		}

		this.messages = function(){
			return {
				password : {
                    required : 'Introduzca una contraseña',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres'
                },
                password_confirmation : {
                    required : 'Confirme la contraseña',
                    minlength : 'Mínimo {0} caracteres',
                    maxlength : 'Máximo {0} caracteres',
                    equalTo : 'Las contraseñas no coinciden',
                }
			}
		}
	}).init()

</script>
@endpush