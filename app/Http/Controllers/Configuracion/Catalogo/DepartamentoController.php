<?php
namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests\DepartamentoRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\DepartamentosDataTable;

/* Models */
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MDepartamento;

/**
 * Controlador del catálogo de departamentos
 */
class DepartamentoController extends BaseController
{
	private $form_id = 'form-departamento';

	/**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
        $this -> setLog('DepartamentoController.log');
    }

	/**
     * Método para mostrar la página inicial de la gestión de los departamentos
     */
	public function index(DepartamentosDataTable $dataTables)
	{
		
		$data['table']    = $dataTables;
		$data['form_id']  = $this ->form_id;
		$data['form_url'] = url('configuracion/catalogos/departamentos/nuevo');

    	return view('Configuracion.Catalogo.Departamento.indexDepartamento') -> with($data);
	}

	/**
     * Método para administrar las peticiones que recibe el controlador
     */
	public function manager(DepartamentoRequest $request)
	{

		switch ($request -> action) {
			case 1: // Nuevo
				$response = $this -> nuevoDepartamento( $request );
				break;
			case 2: // Editar
				$response = $this -> editarDepartamento( $request );
				break;
			case 3: // Visualizar departamento
                $response = $this -> verDepartamento( $request );
                break;
			case 4: // Activar / Desactivar
				$response = $this -> activarDepartamento( $request );
				break;
			case 5: // Eliminar
				$response = $this -> eliminarDepartamento( $request );
				break;
			default:
				return response() -> json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	/**
     * Método para devolver los registros que llenarán la tabla de la página principal
     */
	public function postDataTable(DepartamentosDataTable $dataTables)
	{
		return $dataTables->getData();
	}

	/**
	 * Método para retornar el formulario para crear un nuevo departamento.
	 */
	public function formNuevoDepartamento()
	{
		try {
			$data                  = [];
			$data['title']         = 'Nuevo Departamento';
			$data['url_send_form'] = url('configuracion/catalogos/departamentos/manager');
			$data['form_id']       = $this -> form_id;
			$data['modelo']        = null;
			$data['action']        = 1;
			$data['id']            = Input::get('id');

			$data['direcciones'] = MDireccion::select('DIRE_DIRECCION','DIRE_NOMBRE')
									-> existenteDisponible()
									-> orderBy('DIRE_NOMBRE')
									-> pluck('DIRE_NOMBRE','DIRE_DIRECCION')
									-> toArray();

			return view('Configuracion.Catalogo.Departamento.formDepartamento')->with($data);

		} catch(Exception $error) {

		}
	}

	/**
	 * Método para guardar un nuevo departamento.
	 */
	public function nuevoDepartamento( $request )
	{
		try {
			$departamento = new MDepartamento;
			$departamento -> DEPA_NOMBRE    = $request -> nombre;
			$departamento -> DEPA_DIRECCION = $request -> direccion;
			$departamento -> save();

			$tables = ['dataTableBuilder',null,true];

			$message = sprintf('<i class="fa fa-fw fa-sitemap"></i> Departamento <b>%s</b> creado',$departamento -> getCodigo());

            return $this -> responseSuccessJSON($message,$tables);

		} catch(Exception $error) {

		}
	}

	/**
	 * Método para retornar el formulario para editar el departamento especificado
	 */
	public function formEditarDepartamento(Request $request)
	{
		try {
			$departamento = MDepartamento::findOrFail( $request -> id );
			$data = [];
			$data['title']         = 'Editar departamento';
			$data['url_send_form'] = url('configuracion/catalogos/departamentos/manager');
			$data['form_id']       = $this -> form_id;
			$data['modelo']        = $departamento;
			$data['action']        = 2;
			$data['id']            = $request -> id;

			$data['direcciones'] = MDireccion::select('DIRE_DIRECCION','DIRE_NOMBRE')
									-> existenteDisponible()
									-> orWhere('DIRE_DIRECCION',$departamento -> getKey())
									-> orderBy('DIRE_NOMBRE')
									-> pluck('DIRE_NOMBRE','DIRE_DIRECCION')
									-> toArray();

			return view('Configuracion.Catalogo.Departamento.formDepartamento')->with($data);

		} catch(Exception $error) {

		}
	}

	/**
	 * Método para guardar los cambios realizados a un departamento
	 */
	public function editarDepartamento( $request )
	{
		try {
			$departamento = MDepartamento::find( $request -> id );
			$departamento -> DEPA_NOMBRE    = $request -> nombre;
			$departamento -> DEPA_DIRECCION = $request -> direccion;
			$departamento -> save();

			$message = sprintf('<i class="fa fa-fw fa-check"></i> Departamento <b>%s</b> modificado',$departamento -> getCodigo());

			$tables = 'dataTableBuilder';

			return $this -> responseSuccessJSON($message,$tables);
		} catch(Exception $error) {

		}
	}

	/**
	 * Método para consultar la información de un departamento especificado
	 */
	public function verDepartamento( $request )
    {
        try {
            $departamento         = MDepartamento::find( $request -> id );
            $data['title']        = sprintf('Departamento #%s', $departamento -> getCodigo() );

            $data['detalles'] = [
                ['Código', $departamento -> getCodigo()],
                ['Dirección', $departamento -> Direccion -> getNombre()],
                ['Nombre', $departamento -> getNombre()],
                ['Fecha',  $departamento -> presenter() -> getFechaCreacion()]
            ];

            return view('Configuracion.Catalogo.Departamento.verDepartamento') -> with($data);
        } catch(Exception $error) {

        }

    }

    /**
     * Método para activar o desactivar un departamento
     */
	public function activarDepartamento( $request )
	{
		try {
			$departamento = MDepartamento::findOrFail( $request -> id );
            $departamento -> cambiarDisponibilidad() -> save();
            
            if ( $departamento -> disponible() )
            {
                $message = sprintf('<i class="fa fa-fw fa-check"></i> Departamento <b>%s</b> activado',$departamento -> getCodigo());
                return $this -> responseInfoJSON($message);
            }
            else
            {
                $message = sprintf('<i class="fa fa-fw fa-warning"></i> Departamento <b>%s</b> desactivado',$departamento -> getCodigo());
                return $this -> responseWarningJSON($message);
            }

		} catch(Exception $error) {
			return response() -> json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

	/**
	 * Método para realizar la eliminación de un departamento especificado
	 */
	public function eliminarDepartamento( $request )
	{
		try {
			$departamento = MDepartamento::findOrFail( $request -> id );
			
			$departamento -> eliminar() -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			$message = sprintf('<i class="fa fa-fw fa-warning"></i> Departamento <b>%s</b> eliminado',$departamento -> getCodigo());

			return $this -> responseWarningJSON($message,'danger',$tables);
		} catch(Exception $error) {
			return response() -> json(['status'=>false,'message'=>'Ocurrió un error al eliminar el anexo. Error ' . $error->getMessage() ]);
		}
	}

}
