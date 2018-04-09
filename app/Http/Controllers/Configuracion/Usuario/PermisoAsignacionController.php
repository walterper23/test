<?php
namespace App\Http\Controllers\Configuracion\Usuario;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Cache;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\PermisosDataTable;

/* Models */
use App\Model\Catalogo\MTipoDocumento;
use App\Model\MDocumento;
use App\Model\MPermiso;


class PermisoAsignacionController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this -> setLog('PermisoAsignacionController.log');
	}

	public function index()
	{


		// Recuperar todos los permisos del sistema
		$permisos = Cache::get('Permisos.Sistema');

		// Recuperar todos las direcciones y departamentos disponibles en el sistemas

		// Recuperar la lista de usuarios del sistema

		// Recuperar los permisos del usuario en sesión, para cargarlos en la interfaz
		$permisosUsuario = Cache::get('Permisos.Usuario.Actual') ?? collect();
		dd($permisos, $permisosUsuario, user() -> Permisos, userKey());

		return view('Configuracion.Usuario.indexPermisoAsignacion');
	}

	public function manager(Request $request)
	{

        switch ($request -> action) {
            case 1: // Recuperar
                $response = $this -> recuperarPermisosUsuario( $request );
                break;
            case 2: // Editar
                $response = $this -> editarPermisosUsuario( $request );
                break;
            case 3: // Activar / Desactivar
                $response = $this -> activarAnexo( $request );
                break;
            case 4: // Eliminar
                $response = $this -> eliminarAnexo( $request );
                break;
            default:
                return response()->json(['message'=>'Petición no válida'],404);
                break;
        }
        return $response;
    }


	public function formUsuario()
	{
		return view('Configuracion.Usuario.formUsuario');
	}

}
