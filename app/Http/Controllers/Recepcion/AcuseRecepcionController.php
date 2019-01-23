<?php
namespace App\Http\Controllers\Recepcion;

use Illuminate\Http\Request;
use App\Http\Requests\AnexoRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;
use PDF;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\MAcuseRecepcion;

class AcuseRecepcionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->setLog('AcuseRecepcionController.log');
    }

    public function index(Request $request, $nombre_acuse)
    {
        $mode = $request->get('d',0); // Si el documento será visualizado o descargado por el usuario

        // Buscamos el registro del acuse, y luego la información del documento
        $acuseRecepcion = MAcuseRecepcion::with('Detalle')->where('ACUS_NOMBRE', $nombre_acuse)->limit(1)->first();

        $pdf = $this->makeAcuseRecepcion($acuseRecepcion, $nombre_acuse);       

        if ($mode == 1)
            return $pdf->download( $nombre_acuse );
        else
            return $pdf->stream( $nombre_acuse );
    }

    /**
     * Método para buscar todos los datos a incluir en los acuses de recepción
     */
    public function makeAcuseRecepcion($acuseRecepcion, $nombre_acuse = '')
    {
        $data = [
            'nombre_acuse' => $nombre_acuse,
            'acuse'        => $acuseRecepcion,
            'detalle'      => $acuseRecepcion->Detalle,
            'usuario'      => $acuseRecepcion->Usuario,
            'documento']   => $acuseRecepcion->Documento
        ];

        
        $pdf = PDF::loadView('Recepcion.Acuses.acuseRecepcion', $data, [], [
            'margin_top' => 28, 'margin_bottom' => 20
        ]);

        return $pdf;
    }

}