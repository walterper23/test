<?php
namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests\PuestoRequest;
use Illuminate\Support\Facades\Input;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\PuestosDataTable;

/* Models */
use App\Model\Catalogo\MDepartamento;
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MPuesto;

/**
 * Controlador del catálogo de puestos
 */
class PuestoController extends BaseController
{
	private $form_id = 'form-puesto';

	/**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
        $this -> setLog('PuestoController.log');
    }

	/**
     * Método para mostrar la página inicial de la gestión de los puestos
     */
	public function index(PuestosDataTable $dataTables){

		$data = [
			'table'    => $dataTables,
			'form_id'  => $this -> form_id,
			'form_url' => url('configuracion/catalogos/puestos/nuevo'),
		];

		return view('Configuracion.Catalogo.Puesto.indexPuesto')->with($data);
	}

	/**
     * Método para administrar las peticiones que recibe el controlador
     */
	public function manager(PuestoRequest $request){

		$action = Input::get('action');

		switch ($request -> action) {
			case 1: // Nuevo
				$response = $this -> nuevoPuesto( $request );
				break;
			case 2: // Editar
				$response = $this -> editarPuesto( $request );
				break;
			case 3: // Visualizar puesto
                $response = $this -> verPuesto( $request );
                break;
			case 4: // Activar / Desactivar
				$response = $this -> activarPuesto( $request );
				break;
			case 5: // Eliminar
				$response = $this -> eliminarPuesto( $request );
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
	public function postDataTable(PuestosDataTable $dataTables){
		return $dataTables->getData();
	}

	/**
	 * Método para retornar el formulario para crear un nuevo puesto
	 */
	public function formNuevoPuesto(){
		try {

			$data = [
				'title'         => 'Nuevo puesto',
				'url_send_form' => url('configuracion/catalogos/puestos/manager'),
				'form_id'       => $this -> form_id,
				'modelo'        => null,
				'action'        => 1,
				'id'            => null
			];

			$direcciones = MDireccion::with('departamentos')
									-> select('DIRE_DIRECCION','DIRE_NOMBRE')
									-> existenteDisponible()
									-> orderBy('DIRE_NOMBRE')
									-> get();

			$data['direcciones'] = $direcciones -> pluck('DIRE_NOMBRE','DIRE_DIRECCION') -> toArray();

			$data['departamentos'] = [];

			foreach ($direcciones as $direccion)
			{
				$departamentos = $direccion -> Departamentos() -> where('DEPA_ENABLED',1) -> get();
				foreach ($departamentos as $departamento)
				{
					$data['departamentos'][] = [
						$direccion -> getKey(),
						$departamento -> getKey(),
						$departamento -> getNombre()
					];
				}
			}

			return view('Configuracion.Catalogo.Puesto.formPuesto')->with($data);

		} catch(Exception $error) {

		}
	}

	/**
	 * Método para guardar un nuevo puesto
	 */
	public function nuevoPuesto( $request ){
		try {
			
			$departamento = $request -> departamento;

			if( $departamento == 0 )
				$departamento = null;

			$puesto = new MPuesto;
			$puesto -> PUES_NOMBRE       = $request -> nombre;
			$puesto -> PUES_DIRECCION    = $request -> direccion;
			$puesto -> PUES_DEPARTAMENTO = $departamento;
			$puesto -> save();

			$tables = ['dataTableBuilder',null,true];

			$message = sprintf('<i class="fa fa-fw fa-user-secret"></i> Puesto <b>%s</b> creado',$puesto -> getCodigo());

            return $this -> responseSuccessJSON($message,$tables);
		} catch(Exception $error) {

		}
	}

	/**
	 * Método para retornar el formulario para editar el puesto indicado
	 */
	public function formEditarPuesto(Request $request){
		try {

			$data = [
				'title'         => 'Editar puesto',
				'url_send_form' => url('configuracion/catalogos/puestos/manager'),
				'form_id'       => $this -> form_id,
				'modelo'        => MPuesto::findOrFail( $request -> id ),
				'action'        => 2,
				'id'            => $request -> id
			];

			$direcciones = MDireccion::with('departamentos')
									->select('DIRE_DIRECCION','DIRE_NOMBRE')
									->where('DIRE_ENABLED',1)
									->orderBy('DIRE_NOMBRE')
									->get();

			$data['direcciones'] = $direcciones -> pluck('DIRE_NOMBRE','DIRE_DIRECCION') -> toArray();

			$data['departamentos'] = [];

			foreach ($direcciones as $direccion) {
				$departamentos = $direccion -> Departamentos() -> where('DEPA_ENABLED',1) -> get();
				foreach($departamentos as $departamento){
					$data['departamentos'][] = [
						$direccion -> getKey(),
						$departamento -> getKey(),
						$departamento -> getNombre()
					];
				}

			}

			return view('Configuracion.Catalogo.Puesto.formPuesto')->with($data);

		} catch(Exception $error) {

		}
	}

	/**
	 * Método para guardar los cambios realizados a un puesto
	 */
	public function editarPuesto( $request ){
		try {
			$departamento = $request -> get('departamento');

			if( $departamento == 0 )
				$departamento = null;

			$puesto = MPuesto::findOrFail( $request -> id );
			$puesto -> PUES_NOMBRE       = $request -> nombre;
			$puesto -> PUES_DIRECCION    = $request -> direccion;
			$puesto -> PUES_DEPARTAMENTO = $departamento;
			$puesto -> save();

			$message = sprintf('<i class="fa fa-fw fa-check"></i> Puesto <b>%s</b> modificado',$puesto -> getCodigo());

			$tables = 'dataTableBuilder';

			return $this -> responseSuccessJSON($message,$tables);
		} catch(Exception $error) {

		}
	}

	/**
	 * Método para consultar la información de un puesto indicado
	 */
	public function verPuesto( $request )
    {
        try {
            $puesto = MPuesto::findOrFail( $request -> id );
            $data['title'] = sprintf('Puesto #%s', $puesto -> getCodigo() );

            $data['detalles'] = [
                ['Código', $puesto -> getCodigo()],
                ['Dirección', $puesto -> Direccion -> getNombre()],
                ['Departamento', $puesto -> Departamento ? $puesto -> Departamento -> getNombre() : ''],
                ['Nombre', $puesto -> getNombre()],
                ['Fecha',  $puesto -> presenter() -> getFechaCreacion()]
            ];

            return view('Configuracion.Catalogo.Puesto.verPuesto') -> with($data);
        } catch(Exception $error) {

        }
    }

	/**
	 * Método para activar o desactivar un puesto
	 */
	public function activarPuesto( $request ){
		try {
			$puesto = MPuesto::findOrFail( $request -> id );

			$puesto -> cambiarDisponibilidad() -> save();

			if( $puesto -> disponible() )
			{
				$message = sprintf('<i class="fa fa-fw fa-check"></i> Puesto <b>%s</b> activado',$puesto -> getCodigo());
				return $this -> responseInfoJSON($message);
			}
			else
			{
				$message = sprintf('<i class="fa fa-fw fa-warning"></i> Puesto <b>%s</b> desactivado',$puesto -> getCodigo());
				return $this -> responseWarningJSON($message);
			}

		} catch(Exception $error) {
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

	/**
	 * Método para realizar la eliminación de un puesto
	 */
	public function eliminarPuesto( $request ){
		try {
			$puesto = MPuesto::findOrFail( $request -> id );
			
			$puesto -> eliminar() -> save();

			$message = sprintf('<i class="fa fa-fw fa-warning"></i> Puesto <b>%s</b> eliminado',$puesto -> getCodigo());
			
			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return $this -> responseInfoJSON($message,'danger',$tables);
		} catch(Exception $error) {
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el puesto. Error ' . $error->getMessage() ]);
		}

	}

}