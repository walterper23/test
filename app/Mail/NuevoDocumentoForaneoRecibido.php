<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/* Controllers */
use App\Http\Controllers\Recepcion\AcuseRecepcionController;

class NuevoDocumentoForaneoRecibido extends Mailable implements ShouldQueue
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
        $pdf = $controller -> makeAcuseRecepcion($acuseRecepcion, $acuseRecepcion -> getNombre());

        $view = 'documentos';

        if ($this -> documento -> getTipoDocumento() == 2)
            $view = 'documentos-denuncias';
        if ($this -> documento -> getTipoDocumento() > 2)
            $view = 'documentos';
        
        $url = url( sprintf('recepcion/documentos-foraneos/recepcionados?view=' . $view) );

        $subject = sprintf('Nueva recepción foránea :: %s %s',$this -> documento -> TipoDocumento -> getNombre(), $this -> documento -> getNumero());

        $data = [
            'url' => $url,
            'documento' => $this -> documento
        ];

        return $this -> view('email.nuevoDocumentoRecibido') -> with($data)
                        -> subject($subject)
                        -> attachData($pdf -> Output(), $acuseRecepcion -> getNombre(), [
                            'mime' => 'application/pdf',
                        ]);
    }
}
