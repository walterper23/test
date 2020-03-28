<?php

namespace App\Http\Controllers;

/* Models */
use App\IMEvent;
use Illuminate\Http\Request;

/* Catalogos */
use App\Model\imjuve\IMEntidad;
use App\Model\imjuve\IMVialidades;
use App\Model\imjuve\IMDireccion;
use App\Model\imjuve\IMTipoActividad;
use App\Model\imjuve\IMOrganismo;
use App\Model\imjuve\IMActividad;



class EventController extends Controller
{
    private $form_id = 'form-evento';
    /**
     * Mostrar una lista del recurso.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $eventos = IMEvent::all();
      
      return view('imjuve.Evento.IndexEvento', compact('eventos'));
    }

    /**
     * Mostrar el formulario para crear un nuevo recurso.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$evento = new IMEvent;
        $data['modelo']          = new IMEvent();  
        $data['form_id']        = $this->form_id;
        $data['entidades']          = IMEntidad::getSelect();
        $data['vialidades']         = IMVialidades::getSelect();
        $data['tiposActividades'] = IMTipoActividad::getSelect();
        $data['tipos2'] = IMTipoActividad::all();
        $data['organismos'] = IMOrganismo::all();
        $data['dirigidos'] = IMActividad::all();

        $data['url_send_form'] = url('imjuve/actividades/manager');
        $data['action']        = 1;        
        return view('imjuve.Evento.nuevo')->with($data);
    }

    /**
     * Almacene un recurso recién creado en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $evento = new IMEvent;
        $evento->EVEN_TITULO = $request->eventName;
        $evento->EVEN_TIPO = $request->eventType;
        $evento->EVEN_FECHA = $request->eventDate;
        $evento->EVEN_DESCRIPCION = $request->eventDescription;

        $path = $request->file('eventImg');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $img = $this->String2Hex($base64);

        $evento->EVEN_IMG = $img;
        $evento->EVEN_HORA = $request->eventHour;
        $evento->EVEN_LUGAR = $request->eventPlace;
        $evento->save();

        $eventos = IMEvent::all();
        return view('imjuve.Evento.IndexEvento', compact('eventos'));
        
    }

    function String2Hex($string){
        $hex='';
        for ($i=0; $i < strlen($string); $i++){
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }

    /**
     * Mostrar el recurso especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Mostrar el formulario para editar el recurso especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $evento = IMEvent::Fail($id);
        return view('imjuve.Evento.editar', compact('evento'));
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $evento = IMEvent::findOrFail($id);
        $evento->EVEN_TITULO = $request->eventName;
        $evento->EVEN_TIPO = $request->eventType;
        $evento->EVEN_FECHA = $request->eventDate;
        $evento->EVEN_DESCRIPCION = $request->eventDescription;

        $path = $request->file('eventImg');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $img = $this->String2Hex($base64);
        
        $evento->EVEN_IMG = $img;
        $evento->EVEN_HORA = $request->eventHour;
        $evento->EVEN_LUGAR = $request->eventPlace;
        $evento->save();

        $eventos = IMEvent::all();
        return view('imjuve.Evento.IndexEvento', compact('eventos'));

    }

    /**
     * Eliminar el recurso especificado del almacenamiento.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $evento = IMEvent::findOrFail($id);
      $evento->delete();
      return back();
    }
}
