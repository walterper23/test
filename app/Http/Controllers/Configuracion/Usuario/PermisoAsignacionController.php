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
use App\Model\Catalogo\MDepartamento;
use App\Model\Catalogo\MDireccion;
use App\Model\MDocumento;
use App\Model\MPermiso;
use App\Model\MRecurso;
use App\Model\MUsuario;
use App\Model\MUsuarioPermiso;
use App\Model\MUsuarioAsignacion;


class PermisoAsignacionController extends BaseController
{
	public function __construct()
	{
        $this -> middleware('can:USU.ADMIN.PERMISOS.ASIG');
		parent::__construct();
		$this -> setLog('PermisoAsignacionController.log');
	}

	public function index()
	{
		// Recuperar todos los recursos y permisos del sistema
		$data['recursos'] = MRecurso::with('Permisos') -> get();

		// Recuperar todos las direcciones y departamentos disponibles en el sistema
		$data['direcciones'] = MDireccion::with('DepartamentosExistentes') -> existente() -> orderBy('DIRE_NOMBRE') -> get();

		// Recuperar la lista de usuarios del sistema
		$data['usuarios'] = MUsuario::existente() -> pluck('USUA_USERNAME','USUA_USUARIO') -> toArray();

		$userKey = request() -> get('user', userKey()); // Recuperamos el ID del usuario enviado por la URL, si no, recuperamos le ID del usuario en sesión
		$usuario = MUsuario::where('USUA_USUARIO',$userKey) -> existente() -> first(); // Comprobamos que exista el usuario
		$data['userKey'] = !is_null($usuario) ? $userKey : userKey(); // Almacenamos el ID del usuario si existe. Si no, almacenamos el ID del usuario en sesión

		// Recuperar los permisos del usuario, para cargarlos en la interfaz
		$data['permisosUsuario'] = !is_null($usuario) ? $usuario -> Permisos() -> pluck('SYPE_PERMISO') -> toArray() : [];

        // Recuperar las direcciones asignadas al usuario
        $data['direccionesUsuario'] = $usuario -> Direcciones() -> pluck('DIRE_DIRECCION') -> toArray();

        // Recuperar los departamentos asignados al usuario
        $data['departamentosUsuario'] = $usuario -> Departamentos() -> pluck('DEPA_DEPARTAMENTO') -> toArray();
		
		$data['url_send_form'] = url('configuracion/usuarios/permisos-asignaciones/manager');
		$data['form_id']       = 'form-usuario-asignacion-permiso';

		return view('Configuracion.Usuario.indexPermisoAsignacion') -> with($data);
	}

	public function manager(Request $request)
	{
        switch ($request -> action) {
            case 1: // Recuperar
                $response = $this -> recuperarPermisosAsignacionesUsuario( $request );
                break;
            case 2: // Editar
                $response = $this -> editarPermisosAsignacionesUsuario( $request );
                break;
            default:
                return response()->json(['message'=>'Petición no válida'],404);
                break;
        }
        return $response;
    }

    public function recuperarPermisosAsignacionesUsuario( $request )
    {
        // Recuperamos al usuario solicitado
    	$usuario = MUsuario::with('Permisos','Direcciones','Departamentos') -> where('USUA_USUARIO',$request -> usuario) -> existente() -> first();
        
        // Recuperar los permisos actuales del usuario
        $data['permisos'] = $usuario -> Permisos() -> pluck('USPE_PERMISO') -> toArray();

        // Recuperar las direcciones asignadas al usuario
        $data['direcciones'] = $usuario -> Direcciones() -> pluck('DIRE_DIRECCION') -> toArray();

        // Recuperar los departamentos asignados al usuario
        $data['departamentos'] = $usuario -> Departamentos() -> pluck('DEPA_DEPARTAMENTO') -> toArray();

        return response() -> json($data);

    }

    public function editarPermisosAsignacionesUsuario( $request )
    {
        // Recuperar el usuario y sus permisos
        $usuario = MUsuario::with('Permisos','Direcciones','Departamentos') -> where('USUA_USUARIO',$request -> usuario) -> existente() -> first();
        
        $this -> editarPermisosUsuario( $usuario, $request );

        $this -> editarAsignacionesUsuario( $usuario, $request );

        return $this -> responseSuccessJSON('<i class="fa fa-fw fa-lock"></i> Los cambios se han guardado correctamente');

    }

    public function editarPermisosUsuario( $usuario, $request )
    {
        // Recuperar los permisos actuales del usuario
        $permisosUsuario = $usuario -> Permisos() -> pluck('USPE_PERMISO','USPE_USUARIO_PERMISO') -> toArray();

        // Recuperamos los permisos nuevos para el usuario. Se hace una búsqueda en la base de datos para evitar recibir y agregar permisos que no existan
        $permisosNuevos =  MPermiso::find($request -> permisos) -> pluck('SYPE_PERMISO') -> toArray();

        // Recorrer los permisos del usuario para eliminarle los permisos indicados
        foreach ($permisosUsuario as $usuarioPermiso => $permisoActual) {
            if (! in_array($permisoActual, $permisosNuevos) ) // Si el permiso del usuario no está en los permisos nuevos, se lo eliminamos al usuario
                MUsuarioPermiso::find( $usuarioPermiso ) -> delete();
        }

        // Recorrer los permisos nuevos para agregarle al usuario los permisos que no tenga
        foreach ($permisosNuevos as $permisoActual) {
            if (! in_array($permisoActual, $permisosUsuario) ) // Si el permiso nuevo no está en los permisos del usuario, se lo añadimos al usuario
            {
                $nuevoPermiso = new MUsuarioPermiso;
                $nuevoPermiso -> USPE_USUARIO = $request -> usuario;
                $nuevoPermiso -> USPE_PERMISO = $permisoActual;
                $nuevoPermiso -> save();
            }
        }

        return true;
    }

    // Método para asignarle direcciones y departmamentos a un usuario
    public function editarAsignacionesUsuario( $usuario, $request )
    {
        // Recuperar las direcciones actualmente asignadas al usuario
        $direccionesUsuario = $usuario -> Direcciones() -> pluck('DIRE_DIRECCION','USAS_ASIGNACION') -> toArray();

        // Recuperar los departamentos actualmente asignados al usuario
        $departamentosUsuario = $usuario -> Departamentos() -> pluck('DEPA_DEPARTAMENTO','USAS_ASIGNACION') -> toArray();

        // Recuperamos las direcciones nuevas para el usuario. Se hace una búsqueda en la base de datos para evitar recibir y agregar direcciones que no existan
        $direccionesNuevas =  MDireccion::find($request -> get('direcciones',[]) ) -> pluck('DIRE_DIRECCION') -> toArray();

        // Recuperamos los departamentos nuevos para el usuario. Se hace una búsqueda en la base de datos para evitar recibir y agregar departamentos que no existen
        $departamentosNuevos =  MDepartamento::find($request -> get('departamentos',[]) ) -> pluck('DEPA_DIRECCION','DEPA_DEPARTAMENTO') -> toArray();

        // Recorrer las direcciones del usuario para eliminarle las direcciones indicadas
        foreach ($direccionesUsuario as $asignacion => $direccionActual) {
            if (! in_array($direccionActual, $direccionesNuevas) ) // Si la dirección del usuario no está en las direcciones nuevas, se la eliminamos al usuario
                MUsuarioAsignacion::find( $asignacion ) -> delete();
        }

        // Recorrer las direcciones nuevas para agregarle al usuario las direcciones que no tenga asignadas
        foreach ($direccionesNuevas as $direccionActual) {
            if (! in_array($direccionActual, $direccionesUsuario) ) // Si la dirección nueva no está en las direcciones del usuario, se la añadimos al usuario
            {
                $nuevaDireccion = new MUsuarioAsignacion;
                $nuevaDireccion -> USAS_USUARIO      = $request -> usuario;
                $nuevaDireccion -> USAS_DIRECCION    = $direccionActual;
                $nuevaDireccion -> USAS_DEPARTAMENTO = null;
                $nuevaDireccion -> save();
            }
        }

        // Recorrer los departamentos del usuario para eliminarle los departamentos indicados
        foreach ($departamentosUsuario as $asignacion => $departamentoActual) {
            if (! array_key_exists($departamentoActual, $departamentosNuevos) ) // Si el departamento del usuario no está en los departamentos nuevos, se lo eliminamos al usuario
                MUsuarioAsignacion::find( $asignacion ) -> delete();
        }

        // Recorrer los departamentos nuevas para agregarle al usuario los departamentos que no tenga asignados
        foreach ($departamentosNuevos as $departamentoActual => $direccion) {
            if (! in_array($departamentoActual, $departamentosUsuario) ) // Si el departamento nuevo no está en los departamentos del usuario, se lo añadimos al usuario
            {
                $nuevoDepartamento = new MUsuarioAsignacion;
                $nuevoDepartamento -> USAS_USUARIO      = $request -> usuario;
                $nuevoDepartamento -> USAS_DIRECCION    = $direccion;
                $nuevoDepartamento -> USAS_DEPARTAMENTO = $departamentoActual;
                $nuevoDepartamento -> save();
            }
        }

        return true;

    }

}