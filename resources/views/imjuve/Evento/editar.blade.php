
  <div class="main-panel">
    <div class="content">
      <div class="page-inner">
        <div class="page-header">
          <h4 class="page-title">Eventos</h4>
          
        </div>
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="card-title">Editar evento</div>
              </div>
              <form method="POST" enctype="multipart/form-data" id="formu" class="form-horizontal form-label-left" action="{{route('eventos.update', $evento->EVEN_EVEN)}}">
                {{method_field('PUT')}}
                @csrf
                @include('eventos.fragments.form')
              </form>
            </div>
          </div>
      </div>
    </div>

