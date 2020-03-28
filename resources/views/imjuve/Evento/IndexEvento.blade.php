@extends('app.layoutMaster')

@section('title', title('Configuración de eventos') )

@include('vendor.plugins.datatables')

@section('breadcrumb')
	<nav class="breadcrumb bg-body-light mb-0">
        <a class="breadcrumb-item" href="javascript:void(0)"><i class="fa fa-cogs"></i> Configuraci&oacute;n</a>
        <span class="breadcrumb-item active">Eventos</span>
    </nav>
@endsection

@section('content')
	<!-- Contenido -->
    <div class="main-panel">
    <div class="content">
      <div class="page-inner">
        <div class="page-header">
          <h4 class="page-title">Eventos</h4>
          
          </ul>
        </div>
        <div class="row">

          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="d-flex align-items-center">
                  <h4 class="card-title">Registros de Eventos</h4>
                  <a href="{{route('eventos.create')}}" class="btn btn-success btn-round ml-auto">
                    <i class="fa fa-plus"> </i>
                    Nuevo evento
                  </a>
                </div>
              </div>
              <div class="card-body">
                <!-- Modal -->
                <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header no-bd">
                        <h5 class="modal-title">
                          <span class="fw-mediumbold">
                          New</span>
                          <span class="fw-light">
                            Row
                          </span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <p class="small">Create a new row using this form, make sure you fill them all</p>
                        <form>
                          <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group form-group-default">
                                <label>Name</label>
                                <input id="addName" type="text" class="form-control" placeholder="fill name">
                              </div>
                            </div>
                            <div class="col-md-6 pr-0">
                              <div class="form-group form-group-default">
                                <label>Position</label>
                                <input id="addPosition" type="text" class="form-control" placeholder="fill position">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group form-group-default">
                                <label>Office</label>
                                <input id="addOffice" type="text" class="form-control" placeholder="fill office">
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="modal-footer no-bd">
                        <button type="button" id="addRowButton" class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="table-responsive">
                  <table id="add-row" class="display table table-striped table-hover" >
                    <thead>
                      <tr>
                        <th>Nombre Evento</th>
                        <th>Tipo Evento</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Lugar</th>
                        <th style="width: 10%">Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($eventos as $even)
                        <tr>
                        <td>{{$even->EVEN_TITULO}}</td>
                        <th>{{$even->EVEN_TIPO}}</th>
                          <td>{{$even->EVEN_DESCRIPCION}}</td>
                          <td>{{$even->EVEN_DIA}}/{{$even->EVEN_MES}}/{{$even->EVEN_ANIO}}</td>
                          <td>{{$even->EVEN_HORA}}</td>
                          <td>{{$even->EVEN_LUGAR}}</td>
                          <td>
                            <div class="form-button-action">
                              <form id="demo-form2" data-parsley-validate class="deleteForm pull-left" action="{{route('eventos.destroy',[$even->EVEN_ID]) }}"  method="post">
                              {{ csrf_field() }}

                                {{ method_field('DELETE') }}
                                <button type="submit" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminarlo?')">
                                  <i class="fa fa-times"></i>
                                </button>
                              </form>
                            </div>
                          </td>
                        </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection