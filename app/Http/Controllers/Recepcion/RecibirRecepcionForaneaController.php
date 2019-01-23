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

        $documento->DOFO_RECIBIDO        = 1; // Documento recibido en Oficialía de Partes
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

        $message = sprintf('Documento foráneo <b>#%s</b> recibido', $documento->Documento->getFolio());

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

        $message = sprintf('Documento foráneo <b>#%s</b> validado', $documento->Documento->getFolio());

        return $this->responseSuccessJSON($message,$tables);
    }

    public function recepcionarDocumento( $request )
    {
        try {
            DB::beginTransaction();

                $documentoForaneo = MDocumentoForaneo::with('AcuseRecepcion','Detalle')->find( $request->id );

                if ($documentoForaneo->recepcionado())
                {
                    // El documento ya fue recepcionado
                }

                $documentoForaneo->DOFO_RECEPCIONADO        = 1; // Marcar que el documento ha sido recepcionado por Oficialía de Partes
                $documentoForaneo->DOFO_FECHA_RECEPCIONADO  = Carbon::now(); // Fecha y hora de recepcionado
                $documentoForaneo->save();

                $url_notificacion = 'recepcion/documentos-foraneos/recepcionados?view=';
                
                if ($documentoForaneo->getTipoDocumento() == 1)
                {
                    $tables = ['recibir-denuncias-datatable',null,true];
                    $url_notificacion .= 'denuncias';
                }
                else if ($documentoForaneo->getTipoDocumento() == 2)
                {
                    $tables = ['recibir-documentos-denuncias-datatable',null,true];
                    $url_notificacion .= 'documentos-denuncias';
                }
                else
                {
                    $tables = ['recibir-documentos-datatable',null,true];
                    $url_notificacion .= 'documentos';
                }

                // Crear la notificación sobre de que el documento foráneo ha sido recibido en Oficialía de Partes
                $data = [
                    'contenido'  => sprintf('Documento <b>#%s</b> de tipo <b>%s</b> ha sido recibido y recepcionado exitosamente!',
                                            $documentoForaneo->Documento->getFolio(),$documentoForaneo->TipoDocumento->getNombre()),
                    'url'        => $url_notificacion,
                ];
                
                // Creamos la nueva notificación sobre que el documento foráneo ha sido recibido exitosamente
                NotificacionController::nuevaNotificacion('REC.FOR.DOC.FOR.REC',$data);

                $message = sprintf('Documento foráneo <b>#%s</b> recepcionado', $documentoForaneo->Documento->getFolio());

            DB::commit();

            return $this->responseSuccessJSON($message,$tables);
        } catch (Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON($error->getMessage());
        }
    }
}