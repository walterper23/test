<?php
namespace App\Http\Controllers\Recepcion;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Dashboard\NotificacionController;

/* Models */
use App\Model\MAcuseRecepcion;
use App\Model\MDenuncia;
use App\Model\MDocumento;
use App\Model\MDocumentoForaneo;
use App\Model\MSeguimiento;

/* DataTables */
use App\DataTables\RecibirDenunciasForaneasDataTable;
use App\DataTables\RecibirDocumentosDenunciasForaneasDataTable;
use App\DataTables\RecibirDocumentosForaneosDataTable;

class RecibirRecepcionForaneaController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this->setLog('RecibirRecepcionForaneaController.log');
	}

	public function index(Request $request){

		$tabla1 = new RecibirDenunciasForaneasDataTable();
		$tabla2 = new RecibirDocumentosDenunciasForaneasDataTable();
		$tabla3 = new RecibirDocumentosForaneosDataTable();

		$data['table1'] = $tabla1;
		$data['table2'] = $tabla2;
		$data['table3'] = $tabla3;

		$view  = $request->get('view','denuncias');
		$acuse = $request->get('acuse');

		$data['tab_1'] = '';
		$data['tab_2'] = '';
		$data['tab_3'] = '';

		switch ( $view ) {
			case 'denuncias':
				$data['tab_1'] = ' active';
				break;
			case 'documentos-denuncias':
				$data['tab_2'] = ' active';
				break;
			case 'documentos':
				$data['tab_3'] = ' active';
				break;
			default:
				$data['tab_1'] = ' active';
				break;
		}

		$data['acuse'] = $acuse;

		return view('Recepcion.indexRecibirRecepcionForanea')->with($data);
	}

	public function documentosEnCaptura()
	{
		abort(404);
	}

	public function manager(Request $request){

		switch ($request->action) {
			case 1: // Marcar documentación foránea como recibida
				$response = $this->recibirDocumento( $request );
				break;
			case 2: // Marcar documentación foránea como validada
				$response = $this->validarDocumento( $request );
				break;
			case 3: // Recepcionar documentación foránea
				$response = $this->recepcionarDocumento( $request );
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(Request $request){

		$type = $request->get('type');

		switch ($type) {
			case 'denuncias' :
				$dataTables = new RecibirDenunciasForaneasDataTable;
				break;
			case 'documentos-denuncias':
				$dataTables = new RecibirDocumentosDenunciasForaneasDataTable;
				break;
			case 'documentos':
				$dataTables  = new RecibirDocumentosForaneosDataTable;
				break;
			default:
				$dataTables  = new RecibirDocumentosForaneosDataTable;
				break;
		}

		return $dataTables->getData();
	}

	public function recibirDocumento( $request )
	{
		$documento = MDocumentoForaneo::find( $request->id);

		if ($documento->recibido())
		{
			// El documento ya fue recibido
		}

		$documento->DOFO_SYSTEM_TRANSITO = 2; // Documento recibido en Oficialía de Partes
		$documento->DOFO_FECHA_RECIBIDO  = Carbon::now(); // Fecha y hora de recibido
		$documento->save();

		if ($documento->getTipoDocumento() == 1)
		{
			$tables = ['recibir-denuncias-datatable',null,true];
		}
		else if ($documento->getTipoDocumento() == 2)
		{
			$tables = ['recibir-documentos-denuncias-datatable',null,true];
		}
		else
		{
			$tables = ['recibir-documentos-datatable',null,true];
		}

		$message = sprintf('Documento foráneo <b>#%s</b> recibido', $documento->getFolio());

		return $this->responseSuccessJSON($message,$tables);
	}

	public function validarDocumento( $request )
	{
		$documento = MDocumentoForaneo::find( $request->id);
		
		if ($documento->validado())
		{
			// El documento ya fue validado
		}

		$documento->DOFO_VALIDADO       = 1; // Documento validado por Oficialía de Partes
		$documento->DOFO_FECHA_VALIDADO = Carbon::now(); // Fecha y hora de validado
		$documento->save();

		if ($documento->getTipoDocumento() == 1)
			$tables = ['recibir-denuncias-datatable',null,true];
		else if ($documento->getTipoDocumento() == 2)
			$tables = ['recibir-documentos-denuncias-datatable',null,true];
		else
			$tables = ['recibir-documentos-datatable',null,true];

		$message = sprintf('Documento foráneo <b>#%s</b> validado', $documento->getFolio());

		return $this->responseSuccessJSON($message,$tables);
	}

	public function recepcionarDocumento( $request )
	{
		try {
			DB::beginTransaction();

				$documentoForaneo = MDocumentoForaneo::with('AcuseRecepcion')->find( $request->id );

				$tipo_documento = $documentoForaneo->TipoDocumento;

				if ($documentoForaneo->recepcionado())
				{
					// El documento ya fue recepcionado
				}

				$documento = new MDocumento;
				$documento->DOCU_FOLIO               = 0;
				$documento->DOCU_SYSTEM_TIPO_DOCTO   = $documentoForaneo->getTipoDocumento();
				$documento->DOCU_SYSTEM_ESTADO_DOCTO = 2; // Documento recepcionado
				$documento->DOCU_TIPO_RECEPCION      = 2; // Recepción foránea
				$documento->DOCU_DETALLE             = $documentoForaneo->getDetalle();
				$documento->DOCU_NUMERO_DOCUMENTO    = $documentoForaneo->getNumero();
				$documento->save();

				$documento = MDocumento::find($documento->getKey());

            	$fecha_reinicio = config_var('Adicional.Fecha.Reinicio.Folios');

				if (! is_null($fecha_reinicio) && $fecha_reinicio <= date('Y-m-d H:i:s') )
	            {
	                $documento->DOCU_FOLIO = config_var('Adicional.Folio.Reinicio.Folios');
	                $documento->save();

	                $fecha_reinicio = MSystemConfig::where('SYCO_VARIABLE','Adicional.Fecha.Reinicio.Folios')->limit(1)->first();
	                $fecha_reinicio->SYCO_VALOR = null;
	                $fecha_reinicio->save();

	                MSystemConfig::setAllVariables(); // Volvemos a cargar la variables de configuración al caché
	            }

				// Actualizamos el Documento Denuncia si lo generó el documento foráneo, para actualizar el nuevo ID del documento local
				$documentoForaneo->DocumentoDenuncia()->update([
					'DODE_DOCUMENTO_LOCAL' => $documento->getKey()
				]);

				// Actualizamos los escaneos que generó el documento foráneo, colocandoles el nuevo ID del documento local
				$documentoForaneo->Escaneos()->update([
					'ESCA_DOCUMENTO_LOCAL' => $documento->getKey()
				]);

				$documentoForaneo->DOFO_DOCUMENTO_LOCAL     = $documento->getKey();
				$documentoForaneo->DOFO_RECEPCIONADO        = 1; // Marcar que el documento ha sido recepcionado por Oficialía de Partes
				$documentoForaneo->DOFO_FECHA_RECEPCIONADO  = Carbon::now(); // Fecha y hora de recepcionado
				$documentoForaneo->save();

				// Guardamos el primer seguimiento del documento
				$seguimiento = new MSeguimiento;
				$seguimiento->SEGU_USUARIO              = userKey();
				$seguimiento->SEGU_DOCUMENTO            = $documento->getKey();
				$seguimiento->SEGU_DIRECCION_ORIGEN     = config_var('Sistema.Direcc.Origen');  // Dirección de la recepción, por default
				$seguimiento->SEGU_DEPARTAMENTO_ORIGEN  = config_var('Sistema.Depto.Origen');   // Departamento de la recepción, por default
				$seguimiento->SEGU_DIRECCION_DESTINO    = config_var('Sistema.Direcc.Destino'); // Dirección del procurador, por default
				$seguimiento->SEGU_DEPARTAMENTO_DESTINO = config_var('Sistema.Depto.Destino');  // Departamento del procurador, por default
				$seguimiento->SEGU_ESTADO_DOCUMENTO     = 1; // Documento recepcionado. Estado de documento por default
				$seguimiento->save();

				// Crear el acuse de recepción
            	$folio_acuse = sprintf('ARD/%s/%s/%s/%s/',date('Y'),date('m'),$documento->getFolio(),$tipo_documento->getCodigoAcuse()); // ARD/2018/10/005/DENU/

            	$url_notificacion = 'recepcion/documentos-foraneos/recepcionados?view=';
            	
				if ($documentoForaneo->getTipoDocumento() == 1){

					$denuncia = new MDenuncia; // Crear el registro de la denuncia
					$denuncia->DENU_DOCUMENTO = $documento->getKey();
					$denuncia->save();

                	$folio_acuse .= $denuncia->getFolio(); // ARD/2018/10/005/DENU/001

					$tables = ['recibir-denuncias-datatable',null,true];
					$url_notificacion .= 'denuncias';
				}
				else if ($documentoForaneo->getTipoDocumento() == 2){
                	$folio_acuse .= $documento->DocumentoDenuncia->getCodigo();  // ARD/2018/10/005/DODE/002

					$tables = ['recibir-documentos-denuncias-datatable',null,true];
					$url_notificacion .= 'documentos-denuncias';
				}
				else{
                	$folio_acuse .= $documento->getFolio(); // ARD/2018/10/005/DENU/005

					$tables = ['recibir-documentos-datatable',null,true];
					$url_notificacion .= 'documentos';
				}

				// Creamos el registro del acuse de recepción del documento
				$acuse = new MAcuseRecepcion;
				$acuse->ACUS_NUMERO    = $folio_acuse;
				$acuse->ACUS_NOMBRE    = sprintf('%s.pdf',str_replace('/','_', $folio_acuse));
				$acuse->ACUS_DOCUMENTO = $documento->getKey();
				$acuse->ACUS_CAPTURA   = 1; // Documento localmente
				$acuse->ACUS_DETALLE   = $documentoForaneo->AcuseRecepcion->getDetalle();
				$acuse->ACUS_USUARIO   = $documentoForaneo->AcuseRecepcion->getUsuario();
				$acuse->ACUS_ENTREGO   = $documentoForaneo->AcuseRecepcion->getEntrego();
				$acuse->ACUS_RECIBIO   = $documentoForaneo->AcuseRecepcion->getRecibio();
				$acuse->save();

				// Crear la notificación sobre de que el documento foráneo ha sido recibido en Oficialía de Partes
	            $data = [
	                'contenido'  => sprintf('Documento <b>#%s</b> de tipo <b>%s</b> ha sido recibido y recepcionado exitosamente!',
	                						$documentoForaneo->getFolio(),$documentoForaneo->TipoDocumento->getNombre()),
	                'url'        => $url_notificacion,
	            ];
	            
	            // Creamos la nueva notificación sobre que el documento foráneo ha sido recibido exitosamente
	            NotificacionController::nuevaNotificacion('REC.FOR.DOC.FOR.REC',$data);

				$message = sprintf('Documento foráneo <b>#%s</b> recepcionado', $documentoForaneo->getFolio());

			DB::commit();

			return $this->responseSuccessJSON($message,$tables);
		} catch (Exception $error) {
			DB::rollback();
			return $this->responseDangerJSON($error->getMessage());
		}
	}
}