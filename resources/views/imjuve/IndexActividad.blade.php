@extends('app.layoutMaster')

@section('title', title('Crear actividad') )

@section('content')

<div class="container">
<form action="{{url('imjuve/actividades/crear')}}" method="post" enctype="multipart/form-data">
  {{csrf_field()}}
  <div class="form-group col-md-6">
    <label for="formGroupExampleInput">Nombre</label>
    <input type="text" name="nombre" class="form-control" id="formGroupExampleInput" placeholder="">
  </div>
  <div class="form-group col-md-6">
    <label for="formGroupExampleInput2">Descripción</label>
    <input type="text" name="descripcion" class="form-control" id="formGroupExampleInput2" placeholder="">
  </div>
  <button type="submit" class="btn btn-primary">Guardar</button>

</form>


</div>

@endsection

