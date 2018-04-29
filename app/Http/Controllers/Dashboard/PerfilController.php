<?php
namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Excepcion;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController; 

class PerfilController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this->setLog('PerfilController.log');
	}

	public function index()
	{

		$data['title']         = 'Nuevo usuario';
		$data['form_id']       = 'form-editar-perfil';
		$data['url_send_form'] = url('usuario/perfil/manager');
		$data['usuario']       = user();
		$data['detalle']       = user() -> UsuarioDetalle;

		return view('Dashboard.perfilUsuario') -> with($data);
	}

	public function manager(Request $request)
    {
        switch ($request -> action) {
            case 1: // Editar perfil
                $response = $this -> editarPerfil( $request );
                break;
            default:
                return response() -> json(['message'=>'Petición no válida'],404);
                break;
        }

        return $response;
    }

    public function editarPerfil( $request )
    {
    	try {
	    	// Recuperamos al usuario de la sesión
	    	$usuario = user();

	    	// Comprobamos la contraseña ingresada a la contraseña actual del usuario
    		if (! app('hash') -> check($request -> password_actual , $usuario -> getAuthPassword()) )
    		{
	    		$message = '<i class="fa fa-fw fa-key"></i> Su contraseña actual es incorrecta';
		    	return $this -> responseDangerJSON($message);
    		}

    		DB::beginTransaction();

	    	// Editamos sus campos de información
	    	$usuario -> USUA_NOMBRE   = $request -> descripcion;
	    	
	    	// Si se recibió una contraseña nueva, se la actualizamos al usuario
	    	if ( $request -> has('password') && !is_null($request -> get('password')) )
	    	{
		    	$usuario -> USUA_PASSWORD = $request -> password;
	    	}
	    	
	    	$usuario -> save();

	    	$detalle = user() -> UsuarioDetalle;
	    	$detalle -> USDE_NO_TRABAJADOR = $request -> no_trabajador;
	    	$detalle -> USDE_NOMBRES       = $request -> nombres;
	    	$detalle -> USDE_APELLIDOS     = $request -> apellidos;
	    	$detalle -> USDE_GENERO        = $request -> genero;
	    	$detalle -> USDE_EMAIL         = $request -> email;
	    	$detalle -> USDE_TELEFONO      = $request -> telefono;
	    	$detalle -> save();

	    	DB::commit();

	    	$message = '<i class="fa fa-fw fa-user"></i> Los cambios se han guardado correctamente';
	    	return $this -> responseInfoJSON($message);

	    } catch (Excepcion $error) {
	    	DB::rollback();
	    	return $this -> responseDangerJSON($error -> getMessage());
	    }

    }

}