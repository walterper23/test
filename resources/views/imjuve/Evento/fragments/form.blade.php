<link href="{{ asset('css/multi-select.css') }}" rel="stylesheet">
<style>
  .maxwidth {
    width: 100% !important;
  }
</style>
{{ Form::open(['url'=>$url_send_form,'method'=>'POST','id'=>$form_id,'files'=>true]) }}
{{ Form::hidden('action',$action) }}
<div class="card-body">

  <div class="row">
    <div class="col-md-12 col-lg-12">
      <div class="form-group form-floating-label">
        <input id="inputFloatingLabel" type="text" class="form-control input-border-bottom" name="eventName"
          value="{{$modelo->EVEN_TITULO}}" required>
        <label for="inputFloatingLabel" class="placeholder">Nombre del evento</label>
      </div>
      <div class="row">
        <div class="form-group form-floating-label col-md-4 col-lg-4">
          <select multiple="multiple" id="selectTiposEventos" name="selectTiposEventos[]">
            @foreach ($tipos2 as $tipo)
              <option value="{{$tipo->TACT_ID}}"> {{ $tipo->TACT_NOMBRE }}</option>
            @endforeach
          </select>
          <label for="selectTiposEventos" class="placeholder">Selecciona los tipos de eventos que correspondan:</label>
        </div>

        <div class="form-group form-floating-label col-md-4 col-lg-4">
          <select multiple="multiple" id="selectTiposOrganismos" name="selectTiposOrganismos[]">
            @foreach ($organismos as $organismo)
              <option value="{{$organismo->ORGA_ID}}"> {{ $organismo->ORGA_RAZON_SOCIAL }}</option>
            @endforeach
          </select>
          <label for="selectTiposOrganismos" class="placeholder">Selecciona los tipos de organismos que correspondan:</label>
        </div>

        <div class="form-group form-floating-label col-md-4 col-lg-4">
          <select multiple="multiple" id="selectDirigido" name="selectDirigido[]">
            @foreach ($dirigidos as $dirigido)
              <option value="{{$dirigido->ACTI_ID}}"> {{ $dirigido->ACTI_NOMBRE }}</option>
            @endforeach
          </select>
          <label for="selectDirigido" class="placeholder">Selecciona a quienes vaya dirigido:</label>
        </div>
      </div>

      <div class="row">
        <div class="form-group form-floating-label col-md-4 col-lg-4">
          <input id="inputEventPlace" type="text" class="form-control input-border-bottom" name="eventPlace"
            value="{{$modelo->EVEN_LUGAR}}" required>
          <label for="inputEventPlace" class="placeholder">Lugar del evento</label>
        </div>
        <div class="form-group form-floating-label col-md-4 col-lg-4">
          <input id="inputEventHour" type="time" class="form-control input-border-bottom" name="eventHour"
            value="{{$modelo->EVEN_HORA}}" required>
          <label for="inputEventHour" class="placeholder">Hora del evento</label>
        </div>
        <div class="form-group form-floating-label col-md-4 col-lg-4">
          {!! Field::datepicker('fechaEvento',$modelo->getFechaEvento(),['label'=>'F. Evento','required','placeholder'=>date('Y-m-d'),'popover'=>['Fecha de Evento','Introduzca la fecha del evento']]) !!}        
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 col-lg-6">
          <div class="form-group">
            <label for="comment">Descripción del evento</label>
            <textarea class="form-control" id="comment" rows="5"
              name="eventDescription">{{$modelo->EVEN_DESCRIPCION}}</textarea>
          </div>
        </div>

        <div class="col-md-6 col-lg-6">
          <div class="form-group">
            <label>Seleccione una imagen</label>
            <input type="file" class="form-control-file" name="eventImg" id="file">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div>
        <h3>Dirección</h3>
      </div>
      <div class="row">
        <div class="col-md-6 form-group row">
          <label for="cp" class="col-md-5 col-form-label">Código Postal</label>
          <div class="col-md-7">
            <div class="input-group">
              <input maxlength="5" id="cp" class="form-control" name="cp" type="text"
                value="{{(!is_null($modelo->Direccion))?$modelo->Direccion->getCp():''}}">
              <div class="input-group-appen">
                <button type="button" class="btn btn-secondary">
                  <i class="si si-refresh"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          {!!
          Field::select('entidad',((!is_null($modelo->Direccion))?($modelo->Direccion->getEntidad()):''),['label'=>'Entidad','class'=>'js-select2
          maxwidth',],$entidades) !!}
        </div>
        <div class="col-md-6">
          {!! Field::select('municipio','',['label'=>'Municipio','class'=>'js-select2 maxwidth',],[]) !!}
        </div>
        <div class="col-md-6">
          {!! Field::select('localidad','',['label'=>'Localidad','class'=>'js-select2 maxwidth',],[]) !!}
        </div>
        <div class="col-md-12">
          {!! Field::select('asentamiento','',['label'=>'Colonia/Asentamiento','class'=>'js-select2 maxwidth',],[]) !!}
        </div>
        <div class="col-md-6">
          {!!
          Field::select('tvialidad',((!is_null($modelo->Direccion))?($modelo->Direccion->getTvialidad()):''),['label'=>'Vialidad','class'=>'js-select2
          maxwidth',],$vialidades) !!}
        </div>
        <div class="col-md-6">
          {!!
          Field::text('vialidad',(!is_null($modelo->Direccion))?$modelo->Direccion->getVialidad():'',['label'=>'Nombre','maxlength'=>255])
          !!}
        </div>
        <div class="col-md-6">
          {!! Field::text('next',(!is_null($modelo->Direccion))?$modelo->Direccion->getNext():'',['label'=>'Num
          Ext./Mza','maxlength'=>20]) !!}
        </div>
        <div class="col-md-6">
          {!! Field::text('nint',(!is_null($modelo->Direccion))?$modelo->Direccion->getNint():'',['label'=>'Num
          Int./Lt','maxlength'=>20]) !!}
        </div>


      </div>
    </div>
  </div>


  <div class="card-action">
    <input class="btn btn-success" type="submit" name="save" value="Guardar">
    <a href="{{route('eventos.index')}}" class="btn btn-danger">Cancelar</a>
  </div>

  @push('js-script')
  {{ Html::script('js/jquery.multi-select.js') }}
  @endpush

  @push('js-custom')
  <script type="text/javascript">
    'use strict';
    var formEvento = new AppForm;
    $.extend(formEvento, new function () {

      this.context_ = '#modal-{{ $form_id }}';
      this.form_ = '#{{ $form_id }}';

      this.start = function () {

        var self = this;
        Codebase.helpers(['datepicker', 'select2']);

        self.form.on('keyup keypress', function (e) {
          var code = e.keyCode || e.which;

          if (code === 13) {
            e.preventDefault();
            return false;
          }
        });
        var entidadSelect = $("#entidad");
        var municipioSelect = $("#municipio");
        var localidadSelect = $("#localidad");
        var asentamientoSelect = $("#asentamiento");
        var changeEntidad = this.form.find('#entidad').on('change', function (e) {
          console.log('hola');
          municipioSelect.val(null).trigger('change');
          App.ajaxRequest({
            url: '/imjuve/utils/municipios',
            type: 'POST',
            data: {
              'entidad': e.currentTarget.value
            },
            success: function (result) {
              //municipioSelect.select2('destroy');
              municipioSelect.select2('destroy').off('select2:select');
              municipioSelect.select2();
              console.log('hola');
              $.each(result, function (i, item) {
                var option = new Option(i, item, true, true);
                municipioSelect.append(option);
              });
              municipioSelect.trigger('change');
            },
            error: function (result) {
              resolve(result)
            }
          });
        });
        var changeMunicipio = this.form.find('#municipio').on('change', function (e) {
          if (e.currentTarget.value > 0 && entidadSelect.val() > 0) {
            App.ajaxRequest({
              url: '/imjuve/utils/localidades',
              type: 'POST',
              data: {
                'entidad': entidadSelect.val(),
                'municipio': e.currentTarget.value
              },
              success: function (result) {
                $.each(result, function (i, item) {
                  var option = new Option(i, item, true, true);
                  localidadSelect.append(option);
                });
              },
              error: function (result) {
                resolve(result)
              }
            });
          }
        });
        var changeLocalidad = this.form.find('#localidad').on('change', function (e) {
          App.ajaxRequest({
            url: '/imjuve/utils/asentamientos',
            type: 'POST',
            data: {
              'entidad': entidadSelect.val(),
              'municipio': municipioSelect.val(),
              'localidad': e.currentTarget.value
            },
            success: function (result) {
              $.each(result, function (i, item) {
                var option = new Option(i, item, true, true);
                asentamientoSelect.append(option);
              });
            },
            error: function (result) {
              resolve(result)
            }
          });
        });
      };

      this.rules = function () {
        return {
          nombres: {
            required: true,
            minlength: 1,
            maxlength: 255
          },
          paterno: {
            required: true,
            minlength: 1,
            maxlength: 255
          },
          materno: {
            required: true,
            minlength: 1,
            maxlength: 255
          },
          genero: {
            required: true
          },
          nacimiento: {
            required: true
          },

        }
      }

      this.messages = function () {
        return {
          nombres: {
            required: 'Introduzca los nombre(s) del afiliado',
            minlength: 'Mínimo {0} caracteres',
            maxlength: 'Máximo {0} caracteres'
          },
          paterno: {
            required: 'Introduzca apellido paterno del afiliado',
            minlength: 'Mínimo {0} caracteres',
            maxlength: 'Máximo {0} caracteres'
          },
          materno: {
            required: 'Introduzca apellido materno del afiliado',
            minlength: 'Mínimo {0} caracteres',
            maxlength: 'Máximo {0} caracteres'
          },
          genero: {
            required: 'Seleccione un género'
          },
          nacimiento: {
            required: 'Ingrese la fecha de nacimiento'
          },

        }
      };

      this.displayError = function (index, value) {
        AppAlert.notify({
          type: 'warning',
          icon: 'fa fa-fw fa-warning',
          message: value[0],
          z_index: 9999
        });
      };

    }).init().start();

    //inicializamos multiselect tipos de eventos,tipos de organismos, a quienes va dirigido
    $('#selectTiposEventos').multiSelect();
    $('#selectTiposOrganismos').multiSelect();
    $('#selectDirigido').multiSelect();
  </script>
  @endpush