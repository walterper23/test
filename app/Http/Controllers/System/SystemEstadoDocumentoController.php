<?php
namespace App\Http\Controllers\Configuracion\System;

use Illuminate\Http\Request;
use App\Http\Requests\SystemEstadoDocumentoRequest;
use Exception;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\SystemEstadosDocumentosDataTable;

/* Models */
use App\Model\System\MSystemEstadoDocumento;

class SystemEstadoDocumentoController extends BaseController
{
	private $form_id;

	public function __construct()
	{
		$this -> form_id = 'form-estado-documento';
	}

	public function index(SystemEstadosDocumentosDataTable $dataTables)
	{

		$data['table']    = $dataTables;
		$data['form_id']  = $this -> form_id;
		$data['form_url'] = url('configuracion/sistema/estados-documentos/nuevo');

		return view('Configuracion.Sistema.EstadoDocumento.indexEstadoDocumento') -> with($data);
	}

	public function manager(SystemEstadoDocumentoRequest $request)
	{
		switch ($request -> action) {
			case 2: // Editar
				$response = $this -> editarEstadoDocumento( $request );
				break;
			case 3: // Visualizar estado de documento
				$response = $this -> verEstadoDocumento( $request );
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}

		return $response;
	}

	public function postDataTable(SystemEstadosDocumentosDataTable $dataTables)
	{
		return $dataTables->getData();
	}

	public function formEditarestadoDocumento(Request $request)
	{
		try{

			$data['title']         = 'Editar estado de documento';
			$data['form_id']       = $this -> form_id;
			$data['url_send_form'] = url('configuracion/sistema/estados-documentos/manager');
			$data['action']        = 2;
			$data['model']         = MSystemEstadoDocumento::find( $request -> id );
			$data['id']            = $request -> id;

			return view('Configuracion.Sistema.EstadoDocumento.formEstadoDocumento') -> with($data);
		} catch(Exception $error) {

		}
	}

	public function editarEstadoDocumento( $request )
	{
		try {
			$estadoDocumento = MSystemEstadoDocumento::findOrFail( $request -> id );
			$estadoDocumento -> SYED_NOMBRE = $request -> nombre;
			$estadoDocumento -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			$message = sprintf('<i class="fa fa-fw fa-check"></i> Estado de documento <b>%s</b> modificado',$estadoDocumento -> getCodigo());

			return $this -> responseSuccessJSON($message,$tables);
		} catch(Exception $error) {
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al editar el Estado de Documento. Error ' . $error->getMessage() ]);
		}
	}

	public function verEstadoDocumento( $request )
    {
        try {
            $estadoDocumento = MSystemEstadoDocumento::find( $request -> id );
            $data['title'] = sprintf('Estado de Documento #%s', $estadoDocumento -> getKey() );

            $data['detalles'] = [
                ['Código', $estadoDocumento -> getKey()],
                ['Nombre', $estadoDocumento -> SYED_NOMBRE],
                //['Fecha',  $estadoDocumento -> presenter() -> getFechaCreacion()]
            ];

            return view('Configuracion.Sistema.EstadoDocumento.verEstadoDocumento') -> with($data);
        } catch(Exception $error) {
        	return $error -> getMessage();
        }

    }

}