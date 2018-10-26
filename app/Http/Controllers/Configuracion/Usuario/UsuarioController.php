<?php
namespace App\Http\Controllers\Configuracion\Usuario;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerUsuarioRequest;
use DB;
use Exception;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\UsuariosDataTable;

/* Models */
use App\Model\MUsuario;
use App\Model\MUsuarioDetalle;

/**
 * Controlador para la gestión de los usuarios del sistema
 */
class UsuarioController extends BaseController
{
    private $form_id = 'form-usuario';
    
    /**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
        $this->setLog('UsuarioController.log');
    }

    /**
     * Método para retornar la página principal de la administración de usuarios
     */
    public function index(UsuariosDataTable $dataTables)
    {
        $data['table']    = $dataTables;
        $data['form_id']  = $this->form_id;
        $data['form_url'] = url('configuracion/usuarios/nuevo');

        return view('Configuracion.Usuario.indexUsuario')->with($data);
    }

    /**
     * Método para administrar las peticiones que recibe el controlador
     */
    public function manager(ManagerUsuarioRequest $request)
    {
        switch ($request->action) {
            case 1: // Nuevo
                $response = $this->nuevoUsuario( $request );
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
    public function postDataTable(UsuariosDataTable $dataTables)
    {
        return $dataTables->getData();
    }

    /**
     * Método para retornar el formulario para la creación de un nuevo usuario
     */
    public function formNuevoUsuario()
    {
        $data['title']         = 'Nuevo usuario';
        $data['form_id']       = $this->form_id;
        $data['url_send_form'] = url('configuracion/usuarios/manager');
        $data['action']        = 1;
        
        return view('Configuracion.Usuario.formNuevoUsuario')->with($data);
    }

    /**
     * Método para guardar un nuevo usuario
     */
    public function nuevoUsuario( $request )
    {
        try {

            DB::beginTransaction();
            
            $usuario = new MUsuario;
            $usuario->USUA_USERNAME     = $request->usuario;
            $usuario->USUA_PASSWORD     = bcrypt($request->password);
            $usuario->USUA_DESCRIPCION  = $request->descripcion;
            $usuario->USUA_AVATAR_SMALL = $usuario->getAvatarDefault($request->genero);
            $usuario->USUA_AVATAR_FULL  = $usuario->getAvatarSmall();

            $detalle = new MUsuarioDetalle;
            $detalle->USDE_NO_TRABAJADOR = $request->no_trabajador;
            $detalle->USDE_NOMBRES       = $request->nombres;
            $detalle->USDE_APELLIDOS     = $request->apellidos;
            $detalle->USDE_GENERO        = $request->genero;
            $detalle->USDE_EMAIL         = $request->email;
            $detalle->USDE_TELEFONO      = $request->telefono;
            $detalle->save();

            $usuario->USUA_DETALLE = $detalle->getKey();
            
            $usuario->save();

            DB::commit();

            $tables = ['dataTableBuilder',null,true];

            $message = sprintf('<i class="fa fa-fw fa-user"></i> Usuario <b>%s : %s</b> creado',$usuario->getCodigo(), $usuario->getAuthUsername());

            return $this->responseSuccessJSON($message, $tables);
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseDangerJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
        }

    }

    /**
     * Método para retornar el formulario para editar el usuario especificado
     */
    public function formEditarUsuario(Request $request)
    {
        if ( $request->id == 1 )
        {
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
            return $this->responseDangerJSON('Ocurrió un error: ' . $error->getMessage() );
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
            
            if ($request->password)
            {
                $usuario->USUA_PASSWORD    = $request->password;
            }

            $detalle = $usuario->UsuarioDetalle;
            $detalle->USDE_NO_TRABAJADOR = $request->no_trabajador;
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
            return $this->responseDangerJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
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
        $data['action']        = 5;
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
            $usuario->USUA_PASSWORD = $request->password;
            $usuario->save();

            return $this->responseWarningJSON('<i class="fa fa-key"></i> Contraseña modificada correctamente');

        } catch(Exception $error) {
            return $this->responseDangerJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
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
                return $this->responseDangerJSON('No puede <b>activar/desactivar</b> su usuario si ha iniciado sesión');
            }

        } catch(Exception $error) {
            return $this->responseDangerJSON('Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() );
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
            return $this->responseDangerJSON('Ocurrió un error al eliminar al usuario. Error ' . $error->getCode() );
        }

    }

}