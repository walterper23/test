<?php
namespace App\Http\Controllers\imjuve\Afiliacion;

use Illuminate\Http\Request;
use App\Http\Requests\AfiliacionRequest;
use DB;
use Exception;

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
                $response = $this->editarUsuario( $request );
                break;
            case 3: // Ver
                $response = $this->verUsuario( $request );
                break;
            case 4: // Activar / Desactivar
                $response = $this->activarUsuario( $request );
                break;
            case 5: // Eliminar
                $response = $this->eliminarUsuario( $request );
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
        $data['title']         = 'Nueva afiliación';
        $data['form_id']       = $this->form_id;
        $data['url_send_form'] = url('imjuve/afiliacion/manager');
        $data['action']        = 1;
        $data['generos']            = IMGenero::getSelect();
        $data['escolaridades']      = IMEscolaridad::getSelect();
        $data['estados_civiles']    = IMEstadoCivil::getSelect();
        $data['ocupaciones']        = IMOcupacion::getSelect();
        $data['nacionalidades']     = IMNacionalidad::getSelect();
        $data['entidades']          = IMEntidad::getSelect();
        $data['vialidades']          = IMVialidades::getSelect();

        return view('imjuve.Afiliacion.formNuevaAfiliacion')->with($data);
    }

    /**
     * Método para guardar un nuevo usuario
     */
    public function nuevoAfiliado( $request )
    {
        try {

            DB::beginTransaction();
            dd($request->all());

            $afil = new IMAfiliacion();
            $afil->AFIL_NOMBRES     = $request->usuario;
            $afil->AFIL_PATERNO     = $request->usuario;
            $afil->AFIL_MATERNO     = $request->usuario;
            $afil->AFIL_FECHA_NACIMIENTO     = $request->usuario;
            $afil->AFIL_GENERO_ID     = $request->usuario;
            $afil->AFIL_ESCO_ID     = $request->usuario;
            $afil->AFIL_ESCI_ID     = $request->usuario;
            $afil->AFIL_OCUP_ID     = $request->usuario;
            $afil->AFIL_CORREO     = $request->usuario;
            $afil->AFIL_TELEFONO     = $request->usuario;
            $afil->save();

            $detalle = new IMDireccion();
            $detalle->USDE_NO_TRABAJADOR = $request->notrabajador;
            $detalle->USDE_NOMBRES       = $request->nombres;
            $detalle->USDE_APELLIDOS     = $request->apellidos;
            $detalle->USDE_GENERO        = $request->genero;
            $detalle->USDE_EMAIL         = $request->email;
            $detalle->USDE_TELEFONO      = $request->telefono;
            $detalle->save();

            $afil->USUA_DETALLE = $detalle->getKey();

            $detalle->save();

            DB::commit();

            $tables = ['dataTableBuilder',null,true];

            $message = sprintf('<i class="fa fa-fw fa-user"></i> Usuario <b>%s : %s</b> creado',$usuario->getCodigo(), $usuario->getAuthUsername());

            return $this->responseSuccessJSON($message, $tables);
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
        }

    }

    /**
     * Método para retornar el formulario para editar el usuario especificado
     */
    public function formEditarUsuario(Request $request)
    {
        if ( $request->id == 1 ) {
            abort(403);
        }
        
        try {
            $modelo                = MUsuario::find( $request->id );
            $data['title']         = 'Editar usuario ' . $modelo->getCodigoHash();
            $data['form_id']       = 'form-editar-usuario';
            $data['url_send_form'] = url('configuracion/usuarios/manager');
            $data['modelo']        = $modelo;
            $data['action']        = 2;
            $data['id']            = $request->id;

            return view('Configuracion.Usuario.formEditarUsuario')->with($data);
        } catch(Exception $error) {
            return $this->responseErrorJSON('Ocurrió un error: ' . $error->getMessage() );
        }
    }

    /**
     * Método para guardar los cambios realizados a un usuario
     */
    public function editarUsuario( $request )
    {
        try {

            $usuario = MUsuario::find( $request->id );
            $usuario->USUA_DESCRIPCION = $request->descripcion;
            
            if ($request->password) {
                $usuario->USUA_PASSWORD = bcrypt($request->password);
            }

            $detalle = $usuario->UsuarioDetalle;
            $detalle->USDE_NO_TRABAJADOR = $request->notrabajador;
            $detalle->USDE_NOMBRES       = $request->nombres;
            $detalle->USDE_APELLIDOS     = $request->apellidos;
            $detalle->USDE_GENERO        = $request->genero;
            $detalle->USDE_EMAIL         = $request->email;
            $detalle->USDE_TELEFONO      = $request->telefono;
            $detalle->save();

            $usuario->save();

            DB::commit();

            $message = sprintf('<i class="fa fa-fw fa-check"></i> Usuario <b>%s</b> modificado',$usuario->getCodigo());

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
    public function activarUsuario( $request )
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
    public function eliminarUsuario( $request )
    {
        try {
            $usuario = MUsuario::find( $request->id );
            $usuario->eliminar()->save();

            $tables = 'dataTableBuilder';

            $message = sprintf('<i class="fa fa-fw fa-warning"></i> Usuario <b>%s</b> eliminado',$usuario->getCodigo());

            return $this->responseDangerJSON($message,$tables);
        } catch(Exception $error) {
            return $this->responseErrorJSON('Ocurrió un error al eliminar al usuario. Error ' . $error->getCode() );
        }

    }

}