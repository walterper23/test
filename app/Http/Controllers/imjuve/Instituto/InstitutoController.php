<?php
namespace App\Http\Controllers\imjuve\Instituto;


use App\Http\Requests\InstitutoRequest;
use Illuminate\Http\Request;
use DB;
use Exception;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\imjuve\InstitutoDataTable;

/* Models */
use App\Model\imjuve\IMInstituto;
use App\Model\imjuve\IMEntidad;
use App\Model\imjuve\IMVialidades;


use App\Model\imjuve\IMDireccion;


class InstitutoController extends BaseController
{
    private $form_id = 'form-instituto';
    
   
    public function __construct()
    {
        parent::__construct();
        $this->setLog('InstitutoController.log');
    }

  
  /**
     * @autor Daniel Medina
     * @descrip Método para retornar la página principal de la administración de institutos
     * @date 27/02/2020
     * @version 1.0
     * @param Response $dataTables
     * @return mixed
     */
    public function index(InstitutoDataTable $dataTables)
    {
        $data['table']    = $dataTables;
        $data['form_id']  = $this->form_id;
        $data['form_url'] = url('imjuve/instituto/nuevo');

        return view('imjuve.Instituto.indexinstituto')->with($data);
    }

   /**
     * @autor Daniel Medina
     * @descrip Método para controlar las acciones del instituto
     * @date 27/02/2020
     * @version 1.0
     * @param Request $request
     * @return mixed
     */
    public function manager(InstitutoRequest $request)
    {
       
        switch ($request->action) {
            case 1: // Nuevo
                $response = $this->nuevoInstituto( $request );
                break;
          case 2:  //Editar
                $response = $this->editarInstituto( $request );
                break;
          case 5:  //Eliminar
                $response = $this->eliminarInstituto( $request );
                break;
            default:
                return response()->json(['message'=>'Petición no válida'],404);
                break;
        }

        return $response;
    }

 
    /**
     * @autor Daniel Medina
     * @descrip Método para devolver los registros que llenarán la tabla de la página principal
     * @date 27/02/2020
     * @version 1.0
     * @param Request $dataTables
     * @return mixed
     */
    public function postDataTable(InstitutoDataTable $dataTables)
    {
        return $dataTables->getData();
    }

  /**
     * @autor Daniel Medina
     * @descrip Método para retornar el formulario para la creación de un nuevo instituto
     * @date 29/02/2020
     * @version 1.0
     * @param Request 
     * @return mixed
     */
    public function formNuevoInstituto()
    {
        $data['title']         = 'Nuevo Instituto';
        $data['form_id']       = $this->form_id;
        $data['url_send_form'] = url('imjuve/instituto/manager');
        $data['action']        = 1;
        $data['modelo']        = new IMInstituto();
        $data['entidades']          = IMEntidad::getSelect();
        $data['vialidades']          = IMVialidades::getSelect();

        
        return view('imjuve.Instituto.formEditarInstituto')->with($data);
    }

    /**
     * @autor Daniel Medina
     * @descrip  Método para guardar un nuevo instituto
     * @date 29/02/2020
     * @version 1.0
     * @param Request $request
     * @return mixed
     */
    public function nuevoInstituto($request )
    {
        try {
         

            DB::beginTransaction();
            
            $institucion = new IMInstituto();
            $institucion->ORGA_ALIAS         = $request->organismo;
            $institucion->ORGA_RAZON_SOCIAL  = $request->razon;
            $institucion->ORGA_TELEFONO      = $request->telefono;
            $institucion->save();
             
           $dire = new IMDireccion();
           $dire->DIRE_CP          = $request->cp;
           $dire->DIRE_ENTI_ID     = $request->entidad;
           $dire->DIRE_MUNI_ID     = $request->municipio;
           $dire->DIRE_LOCA_ID     = $request->localidad;
           $dire->DIRE_TASE_ID     = null;
           $dire->DIRE_ASENTAMIENTO    = null;
           $dire->DIRE_ASEN_ID         = $request->asentamiento;
           $dire->DIRE_TVIA_ID         = $request->tvialidad;
           $dire->DIRE_VIALIDAD        = $request->vialidad;
           $dire->DIRE_NUM_EXTERIOR    = $request->next;
           $dire->DIRE_NUM_INTERIOR    = $request->nint;
           $dire->save();
           $institucion->ORGA_DIRE_ID = $dire->getKey();
           $institucion->save();

           if($institucion && $dire){
            DB::commit();
        }

            $tables = ['dataTableBuilder',null,true];

            $message = sprintf('<i class="fa fa-fw fa-check"></i> Instituto <b>%s</b>',' Creado');

            return $this->responseSuccessJSON($message, $tables);
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
        }

    }



     /**
     * @autor Daniel Medina
     * @descrip Método para retornar el formulario para editar el instituto especificado
     * @date 29/02/2020
     * @version 1.0
     * @param Request $request
     * @return mixed
     */
    public function formEditarInstituto(Request $request)
    {
    
        
        try {
            $modelo                = IMInstituto::find( $request->id );
            $data['title']         = 'Editar instituto ' . $modelo->getCodigoHash();
            $data['form_id']       = 'form-editar-instituto';
            $data['url_send_form'] = url('imjuve/instituto/manager');
            $data['modelo']        = $modelo;
            $data['action']        = 2;
            $data['entidades']     = IMEntidad::getSelect();
            $data['vialidades']    = IMVialidades::getSelect();

            return view('imjuve.Instituto.formNuevoInstituto')->with($data);
        } catch(Exception $error) {
            return $this->responseErrorJSON('Ocurrió un error: ' . $error->getMessage() );
        }
    }


     /**
     * @autor Daniel Medina
     * @descrip Método para editar el instituto especificado
     * @date 29/02/2020
     * @version 1.0
     * @param Request $request
     * @return mixed
     */

    public function editarInstituto( $request )
    {
        try {
            DB::beginTransaction();
            
            $institucion = IMInstituto::find( $request->id );
           

            $institucion->ORGA_ALIAS	 = $request->organismo;
            $institucion->ORGA_RAZON_SOCIAL = $request->razon;
            $institucion->ORGA_TELEFONO       = $request->telefono;

            $direccion = IMDireccion::find($institucion->ORGA_DIRE_ID);
            $direccion->DIRE_CP = $request->cp;
            $direccion->DIRE_ENTI_ID = $request->entidad;
            $direccion->DIRE_MUNI_ID = $request->municipio;
            $direccion->DIRE_LOCA_ID = $request->localidad;
            $direccion->DIRE_TVIA_ID = $request->tvialidad;
            $direccion->DIRE_VIALIDAD = $request->vialidad;
            $direccion->DIRE_NUM_EXTERIOR = $request->next;
            $direccion->DIRE_NUM_INTERIOR = $request->nint;
            $direccion->save();

    
            $institucion->save();

            if($institucion && $direccion){
                DB::commit();
            }
        


            $message = sprintf('<i class="fa fa-fw fa-check"></i> Instituto <b>%s</b>',' guardado con éxito');

            $tables = 'dataTableBuilder';

            return $this->responseSuccessJSON($message,$tables);
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
        }
    }

 /**
     * @autor Daniel Medina
     * @descrip Método para eliminar el instituto especificado
     * @date 29/02/2020
     * @version 1.0
     * @param Request $request
     * @return mixed
     */

    public function eliminarInstituto( $request )
    {
        try {
            $institucion = IMInstituto::find( $request->id );
            $institucion->ORGA_ENABLED = 0;
            $institucion->ORGA_DELETED = 1;

            $institucion->eliminar()->save();

            $tables = 'dataTableBuilder';

            $message = sprintf('<i class="fa fa-fw fa-warning"></i> Instituto <b>%s</b> eliminado');

            return $this->responseDangerJSON($message,$tables);
        } catch(Exception $error) {
            return $this->responseErrorJSON('Ocurrió un error al eliminar el instituto Error ' . $error->getCode() );
        }

    }





}