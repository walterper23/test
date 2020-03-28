<?php
/*Autor: Walter Gomez  */
/* Fecha de creación: 23 de Febrero del 2020 */

namespace App\Http\Controllers\imjuve\Actividades;
use Illuminate\Http\Request;
use App\Http\Requests\ManagerActividadRequest;
use DB;
use Exception;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\UsuariosDataTable;
use App\DataTables\imjuve\ActividadDataTable;


/* Models */
use App\Model\imjuve\IMTipoActividad;
use App\Model\imjuve\IMActividad;
use App\Model\MUsuarioDetalle;

/**
 * Controlador para la gestión de las actividades del sistema
 */
class actividadController extends BaseController
{
    private $form_id = 'form-actividad';
    
    /**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
        $this->setLog('actividadController.log');
    }

    /**
     * Método para retornar la página principal de la administración de actividades
     */
    public function index(ActividadDataTable $dataTables)
    {
        $data['table']    = $dataTables;
        $data['form_id']  = $this->form_id;
        $data['form_url'] = url('imjuve/actividades/nuevo');

        return view('imjuve.Actividad.indexActividad')->with($data);
    }

    /**
     * Método para administrar las peticiones que recibe el controlador
     */
    public function manager(ManagerActividadRequest $request) {
        switch ($request->action) {
            case 1: // Nuevo
                $response = $this->nuevoActividad( $request );
                break;
            case 2: // Editar
                $response = $this->editarActividad( $request );
                break;
            case 3: // Ver
                $response = $this->verUsuario( $request );
                break;
            case 4: // Activar / Desactivar
                    $response = $this->activarUsuario( $request );
                    break;
            case 5: // Eliminar
                $response = $this->eliminarActividad( $request );
                break;
            case 6: // Cambiar contraseña
                    $response = $this->modificarPassword( $request );
                    break;
            default:
                return response()->json(['message'=>'Petición no válida'],404);
                break;
        }

        return $response;
    }

    /**
     * Método para devolver los registros que llenarán la tabla de la página principal
     */
    public function postDataTable(ActividadDataTable $dataTables)
    {
        return $dataTables->getData();
    }

    /**
     * Método para retornar el formulario para la creación de una nueva actividad
     */
    public function formNuevoActividad()
    {
        $data['title']         = 'Nuevo Actividad';
        $data['form_id']       = $this->form_id;
        $data['url_send_form'] = url('imjuve/actividades/manager');
        $data['action']        = 1;
        $data['tiposActividades'] = IMTipoActividad::getSelect();
        
        return view('imjuve.Actividad.formNuevoActividad')->with($data);
    }

    /**
     * Método para guardar una nueva actividad
     */
    public function nuevoActividad( $request )
    {
        try {

            DB::beginTransaction();
            
            //IMActividad este modelo    "IMActividad" es en el que guardas los nuevos registros
            $actividad = new IMActividad;
            $actividad->ACTI_NOMBRE = $request->nombre;
            $actividad->ACTI_DESCRIPCION = $request->descripcion;
            $actividad->ACTI_TACT_ID = $request->tipoActividad;
            $actividad->save();
            
            DB::commit();

            $tables = ['dataTableBuilder',null,true];

            //$message = sprintf('<i class="fa fa-fw fa-user"></i> Usuario <b>%s : %s</b> creado',$actividad->getCodigo(), $actividad->getAuthUsername());
            $message = "La actividad se creo con éxito";

            return $this->responseSuccessJSON($message, $tables);
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
        }

    }

    /**
     * Método para retornar el formulario para editar la actividad especificada
     */
    public function formEditarActividad(Request $request)
    {
        if ( $request->id == 1 ) {
            abort(403);
        }
        
        try {
            $modelo                = IMActividad::find( $request->id );
            $data['title']         = 'Editar actividad ' . $modelo->getCodigoHash();
            $data['form_id']       = 'form-editar-actividad';
            $data['url_send_form'] = url('imjuve/actividades/manager');
            $data['modelo']        = $modelo;
            $data['action']        = 2;
            $data['id']            = $request->id;
            $data['tiposActividades'] = IMTipoActividad::getSelect();

            return view('imjuve.Actividad.formEditarActividad')->with($data);
        } catch(Exception $error) {
            return $this->responseErrorJSON('Ocurrió un error: ' . $error->getMessage() );
        }
    }

    /**
     * Método para guardar los cambios realizados a una actividad
     */
    public function editarActividad( $request )
    {
        try {
            
            DB::beginTransaction();

            $actividad = IMActividad::find( $request->id );
            $actividad->ACTI_NOMBRE = $request->nombre;
            $actividad->ACTI_DESCRIPCION = $request->descripcion;
            $actividad->ACTI_TACT_ID = $request->tipoActividad;
            
            $actividad->save();

            DB::commit();

            $tables = ['dataTableBuilder',null,true];
            //$message = sprintf('<i class="fa fa-fw fa-user"></i> Usuario <b>%s : %s</b> creado',$usuario->getCodigo(), $usuario->getAuthUsername());
              $message = "La actividad ha sido modificada con éxito";

            return $this->responseSuccessJSON($message,$tables);
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
        }
    }

    /**
     * Método para realizar la eliminación de una actividad
     */
    public function eliminarActividad( $request )
    {
        try {
            $actividad = IMActividad::find( $request->id );
            $actividad->delete()->save();

            $tables = 'dataTableBuilder';

            $message = sprintf('<i class="fa fa-fw fa-warning"></i> actividad <b>%s</b> eliminado');

            return $this->responseDangerJSON($message,$tables);
        } catch(Exception $error) {
            return $this->responseErrorJSON('Ocurrió un error al eliminar la actividad. Error ' . $error->getCode() );
        }

    }

}