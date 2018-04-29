<?php
namespace App\Http\Controllers\Recepcion;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerAnexoRequest;
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
        $this -> setLog('AcuseRecepcionController.log');
    }

    public function index(Request $request, $nombre_acuse)
    {

        $mode = $request -> get('d',0); // Si el documento será visualizado o descargado por el usuario

        // Buscamos el registro del acuse, y luego la información del documento
        $acuseRecepcion = MAcuseRecepcion::with('Detalle') -> where('ACUS_NOMBRE', $nombre_acuse) -> limit(1) -> first();

        $data = [
            'nombre_acuse' => $nombre_acuse,
            'acuse'        => $acuseRecepcion,
            'detalle'      => $acuseRecepcion -> Detalle,
            'usuario'      => $acuseRecepcion -> Usuario,
        ];

        if ($acuseRecepcion -> getCaptura() == 1) // Acuse de documento local
            $data['documento'] = $acuseRecepcion -> DocumentoLocal;
        else                                      // Acuse de documento foráneo 
            $data['documento'] = $acuseRecepcion -> DocumentoForaneo;

        $pdf = PDF::loadView('Recepcion.Acuses.acuseRecepcion', $data);

        if ($mode == 1)
            return $pdf -> download( $nombre_acuse );
        else
            return $pdf -> stream( $nombre_acuse );

    }


}