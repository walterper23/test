<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/* Controllers */
use App\Http\Controllers\Recepcion\AcuseRecepcionController;

class MailNuevoDocumentoForaneoRecepcionado extends Mailable
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
        $this->documento = $documento;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Recuperamos el acuse de recepción
        $acuseRecepcion = $this->documento->AcuseRecepcion;

        // Generamos el PDF del acuse de recepción
        $controller = new AcuseRecepcionController;
        $pdf = $controller->makeAcuseRecepcion($acuseRecepcion, $acuseRecepcion->getNombre());

        $view = 'documentos';

        if ($this->documento->getTipoDocumento() == 2)
            $view = 'documentos-denuncias';
        if ($this->documento->getTipoDocumento() > 2)
            $view = 'documentos';
        
        $url = url( sprintf('recepcion/documentos-foraneos/recepcionados?view=' . $view) );

        $subject = sprintf('Nueva recepción foránea :: %s %s',$this->documento->TipoDocumento->getNombre(), $this->documento->getNumero());

        $data = [
            'url' => $url,
            'tipo_recepcion' => 'Recepción foránea',
            'documento' => $this->documento
        ];

        $mail = $this->view('email.nuevoDocumentoRecepcionado')->with($data);
        $mail->subject($subject);
        $mail->attachData($pdf->Output(), $acuseRecepcion->getNombre(), ['mime' => 'application/pdf']);

        return $mail;
    }
}
