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
use App\Model\Catalogo\MDireccion;
use App\Model\MDocumento;
use App\Model\MPermiso;
use App\Model\MRecurso;
use App\Model\MUsuario;


class PermisoAsignacionController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this -> setLog('PermisoAsignacionController.log');
	}

	public function index()
	{

		// Recuperar todos los recursos y permisos del sistema
		$data['recursos'] = MRecurso::with(['Permisos' => function($q){
			return $q -> orderBy('SYPE_DESCRIPCION','ASC');
		}]) -> get();

		// Recuperar todos las direcciones y departamentos disponibles en el sistema
		$data['direcciones'] = MDireccion::with('DepartamentosExistentes') -> existente() -> get();

		// Recuperar la lista de usuarios del sistema
		$data['usuarios'] = MUsuario::existente() -> pluck('USUA_USERNAME','USUA_USUARIO') -> toArray();

		$userKey = request() -> get('user', userKey()); // Recuperamos el ID del usuario enviado por la URL, si no, recuperamos le ID del usuario en sesión
		$usuario = MUsuario::where('USUA_USUARIO',$userKey) -> existente() -> first(); // Comprobamos que exista el usuario
		$data['userKey'] = !is_null($usuario) ? $userKey : userKey(); // Almacenamos el ID del usuario si existe. Si no, almacenamos el ID del usuario en sesión

		// Recuperar los permisos del usuario, para cargarlos en la interfaz
		$data['permisosUsuario'] = !is_null($usuario) ? $usuario -> Permisos -> pluck('SYPE_PERMISO') -> toArray() : [];
		
		return view('Configuracion.Usuario.indexPermisoAsignacion') -> with($data);
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
