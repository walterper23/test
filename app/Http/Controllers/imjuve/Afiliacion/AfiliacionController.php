<?php
namespace App\Http\Controllers\imjuve\Afiliacion;

use Illuminate\Http\Request;
use App\Http\Requests\AfiliacionRequest;
use DB;
use Exception;
use Maatwebsite\Excel\Excel;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\imjuve\AfiliacionDataTable;

/* Models */
use App\Model\imjuve\IMAfiliacion;
use App\Model\MUsuarioDetalle;

/* Catalogos */
use App\Model\imjuve\IMGenero;
use App\Model\imjuve\IMEscolaridad;
use App\Model\imjuve\IMEstadoCivil;
use App\Model\imjuve\IMOcupacion;
use App\Model\imjuve\IMNacionalidad;
use App\Model\imjuve\IMEntidad;
use App\Model\imjuve\IMVialidades;
use App\Model\imjuve\IMDireccion;
/**
 * Controlador para la gestión de los usuarios del sistema
 */
class AfiliacionController extends BaseController
{
    private $form_id = 'form-afiliacion';
    
    /**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
        $this->setLog('AfiliacionController.log');
    }

    /**
     * Método para retornar la página principal de la administración de usuarios
     */
    public function index(AfiliacionDataTable $dataTables)
    {
        $data['table']    = $dataTables;
        $data['form_id']  = $this->form_id;
        $data['form_url'] = url('imjuve/afiliacion/nuevo');

        return view('imjuve.Afiliacion.IndexAfiliacion')->with($data);
    }

    /**
     * Método para administrar las peticiones que recibe el controlador
     */
    public function manager(AfiliacionRequest $request)
    {
        switch ($request->action) {
            case 1: // Nuevo
                $response = $this->nuevoAfiliado( $request );
                break;
            case 2: // Editar
                $response = $this->editarAfiliado( $request );
                break;
            case 3: // Ver
                $response = $this->verUsuario( $request );
                break;
            case 4: // Activar / Desactivar
                $response = $this->activarAfiliado( $request );
                break;
            case 5: // Eliminar
                $response = $this->eliminarAfiliado( $request );
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
    public function postDataTable(AfiliacionDataTable $dataTables)
    {
        return $dataTables->getData();
    }

    /**
     * Método para retornar el formulario para la creación de un nuevo afiliado
     */
    public function formNuevaAfiliacion()
    {
        $data['title']          = 'Nueva afiliación';
        $data['form_id']        = $this->form_id;
        $data['url_send_form']  = url('imjuve/afiliacion/manager');
        $data['action']         = 1;
        $data['modelo']          = new IMAfiliacion();
        $data['generos']            = IMGenero::getSelect();
        $data['escolaridades']      = IMEscolaridad::getSelect();
        $data['estados_civiles']    = IMEstadoCivil::getSelect();
        $data['ocupaciones']        = IMOcupacion::getSelect();
        $data['nacionalidades']     = IMNacionalidad::getSelect();
        $data['entidades']          = IMEntidad::getSelect();
        $data['vialidades']         = IMVialidades::getSelect();

        return view('imjuve.Afiliacion.formNuevaAfiliacion')->with($data);
    }

    /**
     * Método para guardar un nuevo usuario
     */
    public function nuevoAfiliado( $request )
    {
        try {
            DB::beginTransaction();

            $afil = new IMAfiliacion();
            $afil->AFIL_NOMBRES     = $request->nombres;
            $afil->AFIL_PATERNO     = $request->paterno;
            $afil->AFIL_MATERNO     = $request->materno;
            $afil->AFIL_FECHA_NACIMIENTO     = $request->nacimiento;
            $afil->AFIL_GENE_ID       = $request->genero;
            $afil->AFIL_ESCO_ID         = $request->escolaridad;
            $afil->AFIL_ESCI_ID         = $request->ecivil;
            $afil->AFIL_OCUP_ID         = $request->ocupacion;
            $afil->AFIL_CORREO          = $request->correo;
            $afil->AFIL_TELEFONO        = $request->telefono;
            $afil->AFIL_FACEBOOK        = $request->facebook;
            $afil->save();

            $dire = new IMDireccion();
            $dire->DIRE_CP          = $request->cp;
            $dire->DIRE_RESIDENCIA  = $request->nacionalidad;
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
            $afil->AFIL_DIRE_ID = $dire->getKey();
            $afil->save();

            if($afil && $dire){
                DB::commit();
            }
            $tables = ['dataTableBuilder',null,true];
            $message = "Afiliado registrado con éxito.";
            return $this->responseSuccessJSON($message, $tables);
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
        }
    }

    /**
     * Método para retornar el formulario para editar el usuario especificado
     */
    public function formEditarAfiliacion(Request $request)
    {
        try {
            $modelo                = IMAfiliacion::find( $request->id );
            $data['title']         = 'Editar afiliado ' . $modelo->getNombreCompleto();
            $data['form_id']       = 'form-editar-afiliado';
            $data['url_send_form'] = url('imjuve/afiliacion/manager');
            $data['modelo']        = $modelo;
            $data['action']        = 2;
            $data['id']            = $request->id;
            $data['generos']            = IMGenero::getSelect();
            $data['escolaridades']      = IMEscolaridad::getSelect();
            $data['estados_civiles']    = IMEstadoCivil::getSelect();
            $data['ocupaciones']        = IMOcupacion::getSelect();
            $data['nacionalidades']     = IMNacionalidad::getSelect();
            $data['entidades']          = IMEntidad::getSelect();
            $data['vialidades']          = IMVialidades::getSelect();

            return view('imjuve.Afiliacion.formEditarAfiliacion')->with($data);
        } catch(Exception $error) {
            return $this->responseErrorJSON('Ocurrió un error: ' . $error->getMessage() );
        }
    }

    /**
     * Método para guardar los cambios realizados a un usuario
     */
    public function editarAfiliado( $request )
    {
        try {
            $afil = null; $dire = null;
            $afil = IMAfiliacion::find($request->id);
            if($afil){
                $afil->AFIL_NOMBRES     = $request->nombres;
                $afil->AFIL_PATERNO     = $request->paterno;
                $afil->AFIL_MATERNO     = $request->materno;
                $afil->AFIL_FECHA_NACIMIENTO     = $request->nacimiento;
                $afil->AFIL_GENE_ID       = $request->genero;
                $afil->AFIL_ESCO_ID         = $request->escolaridad;
                $afil->AFIL_ESCI_ID         = $request->ecivil;
                $afil->AFIL_OCUP_ID         = $request->ocupacion;
                $afil->AFIL_CORREO          = $request->correo;
                $afil->AFIL_TELEFONO        = $request->telefono;
                $afil->AFIL_FACEBOOK        = $request->facebook;
                $afil->save();
                $dire = IMDireccion::find($afil->getDireccion());
                if($dire){
                    $dire->DIRE_CP          = $request->cp;
                    $dire->DIRE_RESIDENCIA  = $request->nacionalidad;
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
                }
            }
            if($afil && $dire){
                DB::commit();
            }
            $message = 'El afiliado fue editado con éxito.';
            $tables = 'dataTableBuilder';

            return $this->responseSuccessJSON($message,$tables);
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
        }
    }

    /**
     * Método para retornar el formulario de cambio de contraseña de un usuario
     */
    public function formPassword()
    {
        $data['title']         = 'Cambiar contraseña';
        $data['form_id']       = 'form-password';
        $data['url_send_form'] = url('configuracion/usuarios/manager');
        $data['action']        = 6;
        $data['usuario']       = MUsuario::find( request()->id )->getAuthUsername();
        $data['id']            = request()->id;

        return view('Configuracion.Usuario.formPassword')->with($data);
    }

    /**
     * Método para guardar las cambios a la contraseña de un usuario
     */
    public function modificarPassword( $request )
    {
        try {
            $usuario = MUsuario::find( $request->id );
            $usuario->USUA_PASSWORD = bcrypt($request->password);
            $usuario->save();

            return $this->responseWarningJSON('<i class="fa fa-key"></i> Contraseña modificada correctamente');

        } catch(Exception $error) {
            return $this->responseErrorJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
        }
    }

    /**
     * Método para activar o desactivar a un usuario indicado
     */
    public function activarAfiliado( $request )
    {
        try {
            // Validar que el usuario en sesión no pueda activar/desactivar su usuario
            if ( $request->id != userKey() )
            {
                $usuario = MUsuario::find( $request->id );
                $usuario->cambiarDisponibilidad()->save();

                if ( $usuario->disponible() )
                {
                    $message = sprintf('<i class="fa fa-check"></i> Usuario <b>%s</b> activado',$usuario->getCodigo());
                    return $this->responseInfoJSON($message);
                }
                else
                {
                    $message = sprintf('<i class="fa fa-warning"></i> Usuario <b>%s</b> desactivado',$usuario->getCodigo());
                    return $this->responseWarningJSON($message);
                }
            }
            else
            {
                return $this->responseErrorJSON('No puede <b>activar/desactivar</b> su usuario si ha iniciado sesión');
            }

        } catch(Exception $error) {
            return $this->responseErrorJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
        }
    }

    /**
     * Método para realizar la eliminación de un usuario
     */
    public function eliminarAfiliado( $request )
    {
        try {

            $afiliacion = IMAfiliacion::find( $request->id );
            $afiliacion->AFIL_ENABLED = 0;
            $afiliacion->AFIL_DELETED = 1;
            $afiliacion->save();
            $tables = 'dataTableBuilder';
            $message = 'El afiliado fue eliminado con éxito.';

            return $this->responseDangerJSON($message,$tables);
        } catch(Exception $error) {
            return $this->responseErrorJSON('Ocurrió un error al eliminar el afiliado. Error ' . $error->getCode() );
        }

    }

}