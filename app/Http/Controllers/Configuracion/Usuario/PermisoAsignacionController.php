<?php
namespace App\Http\Controllers\Configuracion\Usuario;

use Illuminate\Http\Request;
use App\Http\Requests;

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

/**
 * Controlador para gestionar los permisos y las direcciones y departamentos que tiene asignados los usuarios.
 */
class PermisoAsignacionController extends BaseController
{
    /**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
        $this->setLog('PermisoAsignacionController.log');
    }

    /**
     * Método para retornar la página principal para la gestión de los permisos y las asignaciones de los usuarios.
     */
    public function index()
    {
        // Recuperar todos los recursos y permisos del sistema
        $data['recursos'] = MRecurso::with(['Permisos'=>function($permisos){
            $permisos->orderBy('SYPE_DESCRIPCION');
        }])->get();

        // Recuperar todos las direcciones y departamentos disponibles en el sistema
        $data['direcciones'] = MDireccion::with(['DepartamentosExistentes'=>function($departamentosExistentes){
            $departamentosExistentes->orderBy('DEPA_NOMBRE');
        }])->existente()->orderBy('DIRE_NOMBRE')->get();

        // Recuperar la lista de usuarios del sistema
        $usuarios = MUsuario::existente();

        $userKey = request()->get('user', userKey()); // Recuperamos el ID del usuario enviado por la URL, si no, recuperamos le ID del usuario en sesión
        
        $usuario = user();
        if (! user()->isSuperAdmin())
        {
            $usuario = MUsuario::where('USUA_USUARIO',$userKey)->existente()->first(); // Comprobamos que exista el usuario
        }
        else
        {
            $usuarios->orWhere('USUA_USUARIO',1);
        }

        $data['usuarios'] = $usuarios->with('UsuarioDetalle')->siExistenteDisponible()->get()->mapWithKeys(function($item){
            return [ $item->getKey() => sprintf('%s :: %s (%s)', $item->getCodigo(), $item->getAuthUsername(),
                                        $item->UsuarioDetalle->presenter()->getNombreCompleto())];
        });
        
        $data['userKey'] = !is_null($usuario) ? $userKey : userKey(); // Almacenamos el ID del usuario si existe. Si no, almacenamos el ID del usuario en sesión

        // Recuperar los permisos del usuario, para cargarlos en la interfaz
        $data['permisosUsuario'] = !is_null($usuario) ? $usuario->Permisos()->pluck('SYPE_PERMISO')->toArray() : [];

        // Recuperar las direcciones asignadas al usuario
        $data['direccionesUsuario'] = $usuario->Direcciones()->existente()->pluck('DIRE_DIRECCION')->toArray();

        // Recuperar los departamentos asignados al usuario
        $data['departamentosUsuario'] = $usuario->Departamentos()->existente()->pluck('DEPA_DEPARTAMENTO')->toArray();
        
        $data['url_send_form'] = url('configuracion/usuarios/permisos-asignaciones/manager');
        $data['form_id']       = 'form-usuario-asignacion-permiso';

        return view('Configuracion.Usuario.indexPermisoAsignacion')->with($data);
    }

    /**
     * Método para administrar las peticiones que recibe el controlador
     */
    public function manager(Request $request)
    {
        switch ($request->action) {
            case 1: // Recuperar
                $response = $this->recuperarPermisosAsignacionesUsuario( $request );
                break;
            case 2: // Editar
                $response = $this->editarPermisosAsignacionesUsuario( $request );
                break;
            default:
                return response()->json(['message'=>'Petición no válida'],404);
                break;
        }
        return $response;
    }

    /**
     * Método para recuperar los permisos y asignaciones de un usuario
     */
    public function recuperarPermisosAsignacionesUsuario( $request )
    {
        // Recuperamos al usuario solicitado
        $usuario = MUsuario::with('Permisos','Direcciones','Departamentos')->where('USUA_USUARIO',$request->usuario)->existente()->first();
        
        // Recuperar los permisos actuales del usuario
        $data['permisos'] = $usuario->Permisos()->pluck('USPE_PERMISO')->toArray();

        // Recuperar las direcciones asignadas al usuario
        $data['direcciones'] = $usuario->Direcciones()->pluck('DIRE_DIRECCION')->toArray();

        // Recuperar los departamentos asignados al usuario
        $data['departamentos'] = $usuario->Departamentos()->pluck('DEPA_DEPARTAMENTO')->toArray();

        return response()->json($data);
    }

    /**
     * Método para guardar los cambios realizados a los permisos y asignaciones de un usuario
     */
    public function editarPermisosAsignacionesUsuario( $request )
    {

        $id_usuario = $request->get('select2-usuario',0);

        // Recuperar el usuario y sus permisos
        if (user()->isSuperAdmin() && userKey() == $id_usuario)
        {
            $usuario = user();
        }
        else
        {
            $usuario = MUsuario::where('USUA_USUARIO',$id_usuario)->existente()->limit(1)->first();
        }

        if(! $usuario)
        {
            return $this->responseErrorJSON(sprintf('<i class="fa fa-fw fa-warning"></i> Usuario <b>#%s</b> no encontrado',$id_usuario));
        }
        
        // Editamos los permisos del usuario
        $this->editarPermisosUsuario( $usuario, $request );

        // Editamos las asignaciones del usuario
        $this->editarAsignacionesUsuario( $usuario, $request );

        // Eliminamos el caché de los permisos del usuario, para cargarle de nuevo los permisos que debe tener asignados
        cache()->forget(sprintf('Permisos.Usuario.Actual.%d', $usuario->getKey()));

        return $this->responseSuccessJSON('<i class="fa fa-fw fa-lock"></i> Los cambios se han guardado correctamente');
    }

    /**
     * Método para editar los permisos de un usuario.
     */
    public function editarPermisosUsuario( $usuario, $request )
    {
        // Recuperamos los permisos nuevos para el usuario. Se hace una búsqueda en la base de datos para evitar recibir y agregar permisos que no existan
        $permisosNuevos =  MPermiso::find($request->get('permisos',[]))->pluck('SYPE_PERMISO')->toArray();

        $usuario->Permisos()->sync($permisosNuevos);

        return true;
    }

    /**
     * Método para editar las asignaciones de un usuario
     */
    public function editarAsignacionesUsuario( $usuario, $request )
    {
        // Recuperar las direcciones actualmente asignadas al usuario
        $direccionesUsuario = $usuario->Direcciones()->pluck('DIRE_DIRECCION','USAS_ASIGNACION')->toArray();

        // Recuperar los departamentos actualmente asignados al usuario
        $departamentosUsuario = $usuario->Departamentos()->pluck('DEPA_DEPARTAMENTO','USAS_ASIGNACION')->toArray();

        // Recuperamos las direcciones nuevas para el usuario. Se hace una búsqueda en la base de datos para evitar recibir y agregar direcciones que no existan
        $direccionesNuevas =  MDireccion::find($request->get('direcciones',[]) )->pluck('DIRE_DIRECCION')->toArray();

        // Recuperamos los departamentos nuevos para el usuario. Se hace una búsqueda en la base de datos para evitar recibir y agregar departamentos que no existen
        $departamentosNuevos =  MDepartamento::find($request->get('departamentos',[]) )->pluck('DEPA_DEPARTAMENTO')->toArray();

        // Recuperamos las direcciones a eliminar al usuario
        $direccionesExistentes = MDireccion::siExistenteDisponible()->pluck('DIRE_DIRECCION')->toArray();
        $direccionesEliminar   = array_diff($direccionesExistentes, $direccionesNuevas);
        $usuario->UsuarioAsignaciones()->whereIn('USAS_DIRECCION',$direccionesEliminar)->whereNull('USAS_DEPARTAMENTO')->delete();

        $direccionesAgregar = array_diff($direccionesNuevas,$direccionesUsuario);
 
        $usuario->Direcciones()->attach($direccionesNuevas);
        $usuario->Departamentos()->sync($departamentosNuevos);

        return true;
    }

}