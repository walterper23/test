<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

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

        $data = [
            'url' => $url,
            'documento' => $this -> documento
        ];

        return $this -> view('email.nuevoDocumentoRecibido') -> with($data) -> attachData($pdf -> Output(), $acuseRecepcion -> getNombre(), [
            'mime' => 'application/pdf',
        ]);
    }
}
