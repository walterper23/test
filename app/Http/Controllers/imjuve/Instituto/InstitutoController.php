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
use App\Model\imjuve\IMDirecciones;


/**
 * Controlador para la gestión de los usuarios del sistema
 */
class InstitutoController extends BaseController
{
    private $form_id = 'form-instituto';
    
    /**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
        $this->setLog('InstitutoController.log');
    }

    /**
     * Método para retornar la página principal de la administración de usuarios
     */
    public function index(InstitutoDataTable $dataTables)
    {
        $data['table']    = $dataTables;
        $data['form_id']  = $this->form_id;
        $data['form_url'] = url('imjuve/instituto/nuevo');

        return view('imjuve.Instituto.indexinstituto')->with($data);
    }

    /**
     * Método para administrar las peticiones que recibe el controlador
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
         /** 
            case 3:  Ver
                $response = $this->verUsuario( $request );
                break;
            case 4:  Activar / Desactivar
                $response = $this->activarUsuario( $request );
                break;
           
            case 6: Cambiar contraseña
                $response = $this->modificarPassword( $request );
                break;
                  **/
            default:
                return response()->json(['message'=>'Petición no válida'],404);
                break;
        }

        return $response;
    }

    /**
     * Método para devolver los registros que llenarán la tabla de la página principal
     */
    public function postDataTable(InstitutoDataTable $dataTables)
    {
        return $dataTables->getData();
    }

    /**
     * Método para retornar el formulario para la creación de un nuevo usuario
     */
    public function formNuevoInstituto()
    {
        $data['title']         = 'Nuevo Instituto';
        $data['form_id']       = $this->form_id;
        $data['url_send_form'] = url('imjuve/instituto/manager');
        $data['action']        = 1;
        
        return view('imjuve.Instituto.formNuevoInstituto')->with($data);
    }

    /**
     * Método para guardar un nuevo usuario
     */
    public function nuevoInstituto($request )
    {
        try {
         

            DB::beginTransaction();
            
            $institucion = new IMInstituto();
            $institucion->ORGA_ALIAS         = $request->organismo;
            $institucion->ORGA_RAZON_SOCIAL  = $request->razon;
            $institucion->ORGA_TELEFONO      = $request->telefono;

             
           /* $direccion = new IMDirecciones();
            $direccion->DIRE_CP            = $request->codigo_postal;
            $direccion->DIRE_RESIDENCIA    = $request->calle;
            $direccion->DIRE_ASENTAMIENTO     = $request->Asentamiento;
            $direccion->DIRE_VIALIDAD        = $request->vialidad;
            $direccion->DIRE_NUM_EXTERIOR         = $request->noext;
            $direccion->DIRE_NUM_INTERIOR      = $request->noint;

            $direccion->save();

            */
            $institucion->save();

            DB::commit();

            $tables = ['dataTableBuilder',null,true];

            $message = sprintf('<i class="fa fa-fw fa-user"></i> Instituto <b>%s : %s</b> creado');

            return $this->responseSuccessJSON($message, $tables);
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
        }

    }


 /**
     * Método para retornar el formulario para editar el usuario especificado
     */
    public function formEditarInstituto(Request $request)
    {
        if ( $request->id == 1 ) {
            abort(403);
        }
        
        try {
            $modelo                = IMInstituto::find( $request->id );
            $data['title']         = 'Editar institutpo ' . $modelo->getCodigoHash();
            $data['form_id']       = 'form-editar-instituto';
            $data['url_send_form'] = url('imjuve/instituto/manager');
            $data['modelo']        = $modelo;
            $data['action']        = 2;
            $data['id']            = $request->id;

            return view('imjuve.Instituto.formEditarInstituto')->with($data);
        } catch(Exception $error) {
            return $this->responseErrorJSON('Ocurrió un error: ' . $error->getMessage() );
        }
    }

    public function editarInstituto( $request )
    {
        try {
            //Te falto inicializar la transacción
            $institucion = IMInstituto::find( $request->id );

            $institucion->ORGA_ALIAS	 = $request->organismo;
            $institucion->ORGA_RAZON_SOCIAL = $request->razon;
            $institucion->ORGA_TELEFONO       = $request->telefono;
           
            $institucion->save();

        

            DB::commit();

            $message = sprintf('<i class="fa fa-fw fa-check"></i> Instituto <b>%s</b> modificado');

            $tables = 'dataTableBuilder';

            return $this->responseSuccessJSON($message,$tables);
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
        }
    }

    public function eliminarInstituto( $request )
    {
      
        try {
            $institucion = IMInstituto::find( $request->id );
            $institucion->eliminar()->save();

            $tables = 'dataTableBuilder';

            $message = sprintf('<i class="fa fa-fw fa-warning"></i> Instituto <b>%s</b> eliminado');

            return $this->responseDangerJSON($message,$tables);
        } catch(Exception $error) {
            return $this->responseErrorJSON('Ocurrió un error al eliminar el instituto Error ' . $error->getCode() );
        }

    }





}