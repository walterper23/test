<?php
namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests\EstadoDocumentoRequest;
use Illuminate\Support\Facades\Input;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\EstadosDocumentosDataTable;

/* Models */
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MEstadoDocumento;

class EstadoDocumentoController extends BaseController
{
	private $form_id = 'form-estado-documento';

	public function index(EstadosDocumentosDataTable $dataTables)
	{

		$data['table']    = $dataTables;
		$data['form_id']  = $this -> form_id;
		$data['form_url'] = url('configuracion/catalogos/estados-documentos/nuevo');

		return view('Configuracion.Catalogo.EstadoDocumento.indexEstadoDocumento')->with($data);
	}

	public function manager(EstadoDocumentoRequest $request)
	{
		switch ($request -> action) {
			case 1: // Nuevo
				$response = $this -> nuevoEstadoDocumento( $request );
				break;
			case 2: // Editar
				$response = $this -> editarEstadoDocumento( $request );
				break;
			case 3: // Visualizar estado de documento
				$response = $this -> verEstadoDocumento( $request );
				break;
			case 4: // Activar / Desactivar
				$response = $this -> activarEstadoDocumento( $request );
				break;
			case 5: // Eliminar
				$response = $this -> eliminarEstadoDocumento( $request );
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(EstadosDocumentosDataTable $dataTables)
	{
		return $dataTables->getData();
	}

	public function formNuevoEstadoDocumento()
	{
		try {

			$data = [];

			$data['title']         = 'Nuevo estado de documento';
			$data['url_send_form'] = url('configuracion/catalogos/estados-documentos/manager');
			$data['form_id']       = $this -> form_id;
			$data['modelo']		   = null;
			$data['action']		   = 1;
			$data['id']		       = null;

			$direcciones = MDireccion::with(['DepartamentosExistentesDisponibles'=>function($query){
				
				// Si el usuario no puede administrar todos lOs departamentos, buscamos solos los departamentos que tenga asignados		
				if (! (user() -> can('SIS.ADMIN.DEPTOS')) )
				{
					$ids_departamentos = user() -> Departamentos -> pluck('DEPA_DEPARTAMENTO') -> toArray();
					$query -> whereIn('DEPA_DEPARTAMENTO',$ids_departamentos);
				}
				return $query;

			}]) -> select('DIRE_DIRECCION','DIRE_NOMBRE') -> existenteDisponible();

			// Si el usuario no puede administrar todas las direcciones, agregamos la condición de buscar solos las direcciones que tenga asignadas		
			if (! (user() -> can('SIS.ADMIN.DIRECC')) )
			{
				$ids_direcciones = user() -> Direcciones -> pluck('DIRE_DIRECCION') -> toArray();
				$direcciones -> whereIn('DIRE_DIRECCION',$ids_direcciones);
			}

			$direcciones = $direcciones -> orderBy('DIRE_NOMBRE') -> get();

			$data['departamentos'] = [];

			foreach ($direcciones as $direccion)
			{
				$departamentos = $direccion -> DepartamentosExistentesDisponibles;
				foreach ($departamentos as $departamento)
				{
					$data['departamentos'][] = [
						$direccion -> getKey(),
						$departamento -> getKey(),
						$departamento -> getNombre()
					];
				}
			}
			
			$data['direcciones'] = $direcciones -> pluck('DIRE_NOMBRE','DIRE_DIRECCION') -> toArray();

			return view('Configuracion.Catalogo.EstadoDocumento.formEstadoDocumento')->with($data);

		} catch(Exception $error) {

		}
	}

	public function nuevoEstadoDocumento( $request )
	{
		try {

			$departamento = $request -> departamento;

			if( $departamento == 0 )
				$departamento = null;

			$estadoDocumento = new MEstadoDocumento;
			$estadoDocumento -> ESDO_NOMBRE       = $request -> nombre;
			$estadoDocumento -> ESDO_DIRECCION    = $request -> direccion;
			$estadoDocumento -> ESDO_DEPARTAMENTO = $departamento;
			$estadoDocumento -> save();			

			$tables = ['dataTableBuilder',null,true];

			$message = sprintf('<i class="fa fa-fw fa-flash"></i> Estado de documento <b>%s</b> creado',$estadoDocumento -> getCodigo());

			return $this -> responseSuccessJSON($message,$tables);
		
		} catch(Exception $error) {

		}
	}

	public function formEditarEstadoDocumento(Request $request)
	{
		try {
			$data['title']         = 'Editar estado de documento';
			$data['url_send_form'] = url('configuracion/catalogos/estados-documentos/manager');
			$data['form_id']       = $this -> form_id;
			$data['modelo']		   = $modelo = MEstadoDocumento::find( $request -> id );
			$data['action']		   = 2;
			$data['id']		       = $request -> id;

			$direcciones = MDireccion::with(['DepartamentosExistentesDisponibles'=>function($query) use($modelo){
				
				// Agregamos el ID del departamento del Estado de Documento a la lista
				$ids_departamentos[] = $modelo -> getDepartamento();
				// Si el usuario no puede administrar todos los departamentos, buscamos solos los departamentos que tenga asignados		
				if (! (user() -> can('SIS.ADMIN.DEPTOS')) )
				{
					$ids_departamentos += user() -> Departamentos -> pluck('DEPA_DEPARTAMENTO') -> toArray();
				}
				
				$query -> orWhere('DEPA_DEPARTAMENTO',$ids_departamentos);
				
				return $query;

			}]) -> select('DIRE_DIRECCION','DIRE_NOMBRE') -> existenteDisponible();

			// Si el usuario no puede administrar todas las direcciones, agregamos la condición de buscar solos las direcciones que tenga asignadas		
			if (! (user() -> can('SIS.ADMIN.DIRECC')) )
			{
				$ids_direcciones = user() -> Direcciones -> pluck('DIRE_DIRECCION') -> toArray();
				$direcciones -> whereIn('DIRE_DIRECCION',$ids_direcciones);
			}

			// Aseguramos de colocar la dirección del Estado de Documento aunque haya sido desactivada o eliminada
			$direcciones = $direcciones -> orWhere('DIRE_DIRECCION', $modelo -> getDireccion());

			$direcciones = $direcciones -> orderBy('DIRE_NOMBRE') -> get();

			$data['departamentos'] = [];

			foreach ($direcciones as $direccion)
			{
				$departamentos = $direccion -> DepartamentosExistentesDisponibles;
				foreach ($departamentos as $departamento)
				{
					$data['departamentos'][] = [
						$direccion -> getKey(),
						$departamento -> getKey(),
						$departamento -> getNombre()
					];
				}
			}
			
			$data['direcciones'] = $direcciones -> pluck('DIRE_NOMBRE','DIRE_DIRECCION') -> toArray();

			return view('Configuracion.Catalogo.EstadoDocumento.formEstadoDocumento') -> with($data);

		} catch(Exception $error) {

		}
	}

	public function editarEstadoDocumento( $request )
	{
		try {
			$departamento = $request -> departamento;

			if( $departamento == 0 )
				$departamento = null;

			$estadoDocumento = MEstadoDocumento::find( $request -> id );
			$estadoDocumento -> ESDO_NOMBRE       = $request -> nombre;
			$estadoDocumento -> ESDO_DIRECCION    = $request -> direccion;
			$estadoDocumento -> ESDO_DEPARTAMENTO = $departamento;
			$estadoDocumento -> save();			

			$tables = 'dataTableBuilder';

			$message = sprintf('<i class="fa fa-fw fa-flash"></i> Estado de documento <b>%s</b> modificado',$estadoDocumento -> getCodigo());

			return $this -> responseSuccessJSON($message,$tables);

		} catch(Exception $error) {
			
		}
	}

	public function verEstadoDocumento( $request )
    {
        try {
            $estado        = MEstadoDocumento::find( $request -> id );
            $data['title'] = sprintf('Estado de Documento #%s', $estado -> getCodigo() );

            $data['detalles'] = [
                ['Código', $estado -> getCodigo()],
                ['Dirección', $estado -> Direccion -> getNombre()],
                ['Departamento', $estado -> Departamento ? $estado -> Departamento -> getNombre() : ''],
                ['Nombre', $estado -> getNombre()],
                ['Fecha',  $estado -> presenter() -> getFechaCreacion()]
            ];

            return view('Configuracion.Catalogo.EstadoDocumento.verEstadoDocumento') -> with($data);
        } catch(Exception $error) {

        }

    }

	public function activarEstadoDocumento( $request )
	{
		try {
			$estadoDocumento = MEstadoDocumento::find( $request -> id );
            $estadoDocumento -> cambiarDisponibilidad() -> save();
            
            if ( $estadoDocumento -> disponible() )
            {
                $message = sprintf('<i class="fa fa-fw fa-check"></i> Estado de Documento <b>%s</b> activado',$estadoDocumento -> getCodigo());
                return $this -> responseInfoJSON($message);
            }
            else
            {
                $message = sprintf('<i class="fa fa-fw fa-warning"></i> Estado de Documento <b>%s</b> desactivado',$estadoDocumento -> getCodigo());
                return $this -> responseWarningJSON($message);
            }

            return $this -> responseTypeJSON($message,$type);
		} catch(Exception $error) {
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}


	public function eliminarEstadoDocumento( $request )
	{
		try {
			$estadoDocumento = MEstadoDocumento::find( $request -> id );
			$estadoDocumento -> eliminar() -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			$message = sprintf('<i class="fa fa-fw fa-warning"></i> Estado de Documento <b>%s</b> eliminado',$estadoDocumento -> getCodigo());

            return $this -> responseWarningJSON($message,'danger',$tables);
		} catch(Exception $error) {
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el estado de documento. Error ' . $error->getMessage() ]);
		}
	}

}