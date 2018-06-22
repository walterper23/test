<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Storage;

/* Controllers */
use App\Http\Controllers\Recepcion\AcuseRecepcionController;

class NuevoDocumentoRecibido extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $documento;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($documento)
    {
        $this -> documento = $documento;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $acuseRecepcion = $this -> documento -> AcuseRecepcion;

        $controller = new AcuseRecepcionController;
        $pdf = $controller -> makeAcuseRecepcion($acuseRecepcion, $acuseRecepcion -> getNombre() );

        $seguimiento = $this -> documento -> Seguimientos -> first();

        $url = url( sprintf('panel/documentos/seguimiento?search=%d&read=1', $seguimiento -> getKey()) );

        $subject = sprintf('Nueva recepción :: %s %s',$this -> documento -> TipoDocumento -> getNombre(), $this -> documento -> getNumero());

        $data = [
            'url' => $url,
            'documento' => $this -> documento
        ];

        $mail = $this -> view('email.nuevoDocumentoRecibido')
                        -> with($data)
                        -> subject($subject)
                        -> attachData($pdf -> Output(), $acuseRecepcion -> getNombre(), [
                                'mime' => 'application/pdf',
                            ]);

        /*
        if( ($escaneos = $this -> documento -> Escaneos) -> count() > 0 )
        {
            if($escaneo = $escaneos -> values() -> get(0))
            {
                //$mail -> attachData($pdf -> Output(), $acuseRecepcion -> getNombre(), ['mime' => 'application/pdf']);
            }

            if($escaneo = $escaneos -> values() -> get(9))
            {
                //$mail -> attachData($pdf -> Output(), $acuseRecepcion -> getNombre(), ['mime' => 'application/pdf']);
            }
        }
        */

        return $mail;
    }
}
