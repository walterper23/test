<?php
namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerUsuarioRequest;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\MUsuario;
use App\Model\MDocumento;
use App\Model\MSeguimiento;
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MEstadoDocumento;

class PanelController extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this -> setLog('PanelController.log');
	}

	public function index(Request $request){

		$view = $request -> get('view','all');

		// Recuperar las direcciones asignadas al usuario
		$direcciones = user() -> Direcciones;
		$ids_direcciones = $direcciones -> pluck('DIRE_DIRECCION') -> toArray();

		// Recuperar los departamentos asignados al usuario
		$departamentos = user() -> Departamentos;
		$ids_departamentos = $departamentos -> pluck('DEPA_DEPARTAMENTO') -> toArray();

		/* 
		*  Recuperar los seguimientos que hayan pasado por las direcciones y departamentos anteriores
		*  Los seguimientos nos darán documentos, y si estos se repiten, se deberá recuperar el documento
		*  con el seguimiento más reciente
		*/
		$seguimientos = MSeguimiento::
						with('Documento','DireccionOrigen','DireccionDestino','DepartamentoOrigen','DepartamentoDestino','EstadoDocumento')
						-> distinct('SEGU_DOCUMENTO')
						-> leftJoin('documentos','SEGU_DOCUMENTO','=','DOCU_DOCUMENTO')
						-> leftJoin('system_tipos_documentos','SYTD_TIPO_DOCUMENTO','=','DOCU_SYSTEM_TIPO_DOCTO')
						-> leftJoin('detalles','DETA_DETALLE','=','DOCU_DETALLE')
						-> leftJoin('usuarios','USUA_USUARIO','=','SEGU_USUARIO')
						-> leftJoin('usuarios_detalles','USDE_USUARIO_DETALLE','=','USUA_DETALLE')
						-> whereIn('SEGU_DIRECCION_ORIGEN',$ids_direcciones)
						-> orWhereIn('SEGU_DIRECCION_DESTINO',$ids_direcciones)
						-> orWhereIn('SEGU_DEPARTAMENTO_ORIGEN',$ids_departamentos)
						-> orWhereIn('SEGU_DEPARTAMENTO_DESTINO',$ids_departamentos)
						-> orderBy('SEGU_SEGUIMIENTO','DESC')
						-> get();

		// Creamos un contenedor donde se almacenarán los seguimientos y documentos de acuerdo a ciertas clasificaciones
		$documentos = [
			'recientes'   => [], // Documentos que el usuario aún no ha leído
			'todos'       => [], // Todos los documentos encontrados
			'importantes' => [], // Los documentos que el usuario ha marcado como importantes
			'archivados'  => [], // Los documentos que el usuario ha marcado como archivados
			'finalizados' => [], // Los documentos que ya fueron finalizados
		];

		// Recorremos todos los seguimientos encontrados y vamos guardando en el contenedor anterior
		foreach ($seguimientos as $seguimiento) {
			
			// Añadimos el seguimiento a Todos
			$documentos['todos'][] = $seguimiento;
			
			// Si el usuario no ha leido el seguimiento, lo añadimos a Recientes
			if (! $seguimiento -> seguimientoLeido() )
			{
				$documentos['recientes'][] = $seguimiento;
			}

		}
		//

		//

		//
			
		switch ($view) {
			case 'recents':
				$data['title']      = 'Documentos recientes';
				$data['documentos'] = $documentos['recientes'];
				break;
			case 'all':
				$data['title']      = 'Documentos';
				$data['documentos'] = $documentos['todos'];
				break;
			case 'important':
				$data['title']      = 'Documentos importantes';
				$data['documentos'] = $documentos['importantes'];
				break;
			case 'archived':
				$data['title']      = 'Documentos archivados';
				$data['documentos'] = $documentos['archivados'];
				break;
			case 'finished':
				$data['title']      = 'Documentos finalizados';
				$data['documentos'] = $documentos['finalizados'];
				break;
			
			default:
				$data['title'] = 'Documentos recientes';
				$data['documentos'] = $documentos['todos'];
				break;
		}


		return view('Panel.Documentos.index') -> with($data);

	}

	public function manager(Request $request){

        switch ($request -> action) {
            case 1: // Formulario de cambio de estado
                $response = $this -> formCambioEstadoDocumento( $request );
                break;
            case 2: // Nuevo cambio de estado de documento
                $response = $this -> cambiarEstadoDocumento( $request );
                break;
            default:
                return response()->json(['message'=>'Petición no válida'],404);
                break;
        }
        return $response;
    }


	public function documentosRecientes()
	{

	}

	public function documentosTodos()
	{
		
	}

	public function documentosImportantes()
	{

	}

	public function documentosFinalizados()
	{

	}

	public function formCambioEstadoDocumento( $request )
	{
		$data = [
			'title'         => 'Cambio de Estado de Documento',
			'url_send_form' => url('panel/documentos/manager'),
			'form_id'       => 'form-cambio-estado-documento',
			'action'        => 2,
			'id' => 2,
		];


		// Obtener las direcciones asignadas al usuario
		$data['direcciones_origen'] = user() -> Direcciones -> pluck('DIRE_NOMBRE','DIRE_DIRECCION') -> toArray();

		// Obtener los departamentos asignados al usuario
		$usuario_departamentos = user() -> Departamentos() -> with('Direccion') -> get();

		$data['departamentos_origen'] = [];
		foreach ($usuario_departamentos as $departamento)
		{
			$data['departamentos_origen'][] = [
				$departamento -> Direccion -> getKey(),
				$departamento -> getKey(),
				$departamento -> getNombre()
			];
		}

		// Obtener todas las direcciones de destino disponibles
		$direcciones = MDireccion::with('Departamentos')
							-> select('DIRE_DIRECCION','DIRE_NOMBRE')
							-> where('DIRE_ENABLED',1)
							-> orderBy('DIRE_NOMBRE')
							-> get();

		$data['direcciones_destino'] = $direcciones -> pluck('DIRE_NOMBRE','DIRE_DIRECCION') -> toArray();
		
		// Obtener todos los departamentos de destino disponibles
		$data['departamentos_destino'] = [];

		foreach ($direcciones as $direccion)
		{
			$departamentos = $direccion -> Departamentos() -> where('DEPA_ENABLED',1) -> get();
			foreach ($departamentos as $departamento)
			{
				$data['departamentos_destino'][] = [
					$direccion -> getKey(),
					$departamento -> getKey(),
					$departamento -> getNombre()
				];
			}
		}
	
		// Obtener los estados de documentos de sus direcciones y departamentos
		$estados = MEstadoDocumento::all()->pluck('ESDO_NOMBRE','ESDO_ESTADO_DOCUMENTO')->toArray();

		$data['estados'] = $estados;

		return view('Panel.Documentos.formCambioEstadoDocumento') -> with($data);
	}

}