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
use App\Model\MMarcador;
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

	public function index(Request $request)
	{

		$view = $request -> get('view','all');

		// Recuperar las direcciones asignadas al usuario
		$direcciones = user() -> Direcciones;
		$ids_direcciones = $direcciones -> pluck('DIRE_DIRECCION') -> toArray();

		// Recuperar los departamentos asignados al usuario
		$departamentos = user() -> Departamentos;
		$ids_departamentos = $departamentos -> pluck('DEPA_DEPARTAMENTO') -> toArray();

		/* 
		*  Recuperar los seguimientos que hayan pasado por las direcciones y departamentos anteriores
		*  Los seguimientos nos darán los documentos en los cuáles ha participado el usuario
		*/

		$documentos = MSeguimiento::selectRaw('distinct(SEGU_DOCUMENTO) as id_documento')
						-> whereIn('SEGU_DIRECCION_ORIGEN',$ids_direcciones)
						-> orWhereIn('SEGU_DIRECCION_DESTINO',$ids_direcciones)
						-> orWhereIn('SEGU_DEPARTAMENTO_ORIGEN',$ids_departamentos)
						-> orWhereIn('SEGU_DEPARTAMENTO_DESTINO',$ids_departamentos)
						-> pluck('id_documento')
						-> toArray();

		// Recuperar el último seguimiento de cada documento
		$seguimientos = MSeguimiento::with('Documento','DireccionOrigen','DireccionDestino','DepartamentoOrigen','DepartamentoDestino','EstadoDocumento')
						-> leftJoin('documentos','SEGU_DOCUMENTO','=','DOCU_DOCUMENTO')
						-> leftJoin('system_tipos_documentos','SYTD_TIPO_DOCUMENTO','=','DOCU_SYSTEM_TIPO_DOCTO')
						-> leftJoin('detalles','DETA_DETALLE','=','DOCU_DETALLE')
						-> leftJoin('usuarios','USUA_USUARIO','=','SEGU_USUARIO')
						-> leftJoin('usuarios_detalles','USDE_USUARIO_DETALLE','=','USUA_DETALLE')
						-> leftJoin('documentos_marcadores','DOMA_DOCUMENTO','=','DOCU_DOCUMENTO')
						-> whereIn('SEGU_DOCUMENTO',$documentos)
						-> whereRaw('SEGU_SEGUIMIENTO in (select max(SEGU_SEGUIMIENTO) from seguimiento group by SEGU_DOCUMENTO order by SEGU_SEGUIMIENTO desc)')
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
				$seguimiento -> leido = false;
				$documentos['recientes'][] = $seguimiento;
			}

			// Si el usuario tiene marcado el documento como Importante, lo añadimos a Importantes
			if (strpos($seguimiento -> DOMA_IMPORTANTE,strval(userKey())) !== false)
			{
				$seguimiento -> importante = true;
				$documentos['importantes'][] = $seguimiento;
			}

			// Si el usuario tiene marcado el documento como Archivado, lo añadimos a Archivados
			if (strpos($seguimiento -> DOMA_ARCHIVADO,strval(userKey())) !== false)
			{
				$documentos['archivados'][] = $seguimiento;
			}

			// Si el documento ya está resuelto
			if ($seguimiento -> Documento -> resuelto())
			{
				$documentos['finalizados'][] = $seguimiento;
			}


		}
		
		// 
		switch ($view) {
			case 'recents':
				$data['title']      = 'Documentos recientes';
				$data['documentos'] = $documentos['recientes'];
				break;
			case 'all':
				$data['title']      = 'Documentos recibidos';
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
				$data['title'] = 'Documentos recibidos';
				$data['documentos'] = $documentos['todos'];
				break;
		}

		$data['recientes']   = sizeof($documentos['recientes']);
		$data['todos']       = sizeof($documentos['todos']);
		$data['importantes'] = sizeof($documentos['importantes']);
		$data['archivados']  = sizeof($documentos['archivados']);
		$data['finalizados'] = sizeof($documentos['finalizados']);

		return view('Panel.Documentos.index') -> with($data);

	}

	public function manager(Request $request)
	{

        switch ($request -> action) {
            case 1: // Formulario de cambio de estado
                $response = $this -> formCambioEstadoDocumento( $request );
                break;
            case 2: // Nuevo cambio de estado de documento
                $response = $this -> cambiarEstadoDocumento( $request );
                break;
            case 3: // Marcar documento como importante
                $response = $this -> marcarDocumentoImportante( $request );
                break;
            default:
                return response()->json(['message'=>'Petición no válida'],404);
                break;
        }
        return $response;
    }

    // Formulario para realizar el cambio de estado de un documento
	public function formCambioEstadoDocumento( $request )
	{
		$data = [
			'title'         => 'Cambio de Estado de Documento',
			'url_send_form' => url('panel/documentos/manager'),
			'form_id'       => 'form-cambio-estado-documento',
			'action'        => 2,
			'seguimiento'   => $request -> seguimiento,
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
		$estados = MEstadoDocumento::select('ESDO_NOMBRE','ESDO_ESTADO_DOCUMENTO')
					-> where('ESDO_ENABLED',1)
					-> where('ESDO_DELETED',0)
					-> where(function($query){

						// Recuperar las direcciones asignadas al usuario
						$direcciones = user() -> Direcciones;
						$ids_direcciones = $direcciones -> pluck('DIRE_DIRECCION') -> toArray();

						// Recuperar los departamentos asignados al usuario
						$departamentos = user() -> Departamentos;
						$ids_departamentos = $departamentos -> pluck('DEPA_DEPARTAMENTO') -> toArray();

						$query -> orWhereIn('ESDO_DIRECCION',$ids_direcciones);
						$query -> orWhereIn('ESDO_DEPARTAMENTO',$ids_departamentos);
					})
					-> pluck('ESDO_NOMBRE','ESDO_ESTADO_DOCUMENTO')
					-> toArray();

		$data['estados'] = $estados;

		return view('Panel.Documentos.formCambioEstadoDocumento') -> with($data);
	}

	// Método para cambiar el estado de un documento
	public function cambiarEstadoDocumento( $request )
	{

		$anterior = MSeguimiento::find( $request -> seguimiento );
		
		$seguimiento = new MSeguimiento;
		$seguimiento -> SEGU_USUARIO              = userKey();
		$seguimiento -> SEGU_DOCUMENTO            = $anterior -> SEGU_DOCUMENTO;
		$seguimiento -> SEGU_DIRECCION_ORIGEN     = $request -> direccion_origen;
		$seguimiento -> SEGU_DEPARTAMENTO_ORIGEN  = $request -> departamento_origen;
		$seguimiento -> SEGU_DIRECCION_DESTINO    = $request -> direccion_destino;
		$seguimiento -> SEGU_DEPARTAMENTO_DESTINO = $request -> departamento_destino;
		$seguimiento -> SEGU_ESTADO_DOCUMENTO     = $request -> estado;
		$seguimiento -> SEGU_OBSERVACION          = $request -> observacion;
		$seguimiento -> SEGU_INSTRUCCION          = $request -> instruccion;
		$seguimiento -> save();

		return $this -> responseSuccessJSON();

	}

	// Método para marcar como Importante un documento para el usuario
	public function marcarDocumentoImportante( $request )
	{
		try {

			$documento = MDocumento::with('Marcadores') -> find( $request ->  documento );
			$marcadores = $documento -> Marcadores;

			if (is_null($marcadores))
			{
				$marcadores = new MMarcador;
				$marcadores -> DOMA_DOCUMENTO  = $documento -> getKey();
				$marcadores -> DOMA_IMPORTANTE = userKey();
			}
			else
			{
				$marcadores -> marcarImportante();
			}
			
			$marcadores -> save();

			return $this -> responseSuccessJSON();

		} catch(Exception $error) {

		}

	}

}