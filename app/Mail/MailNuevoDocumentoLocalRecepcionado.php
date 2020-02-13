<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/* Controllers */
use App\Http\Controllers\Recepcion\AcuseRecepcionController;

class MailNuevoDocumentoLocalRecepcionado extends Mailable
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
        $pdf = $controller->makeAcuseRecepcion($acuseRecepcion, $acuseRecepcion->getNombre() );

        // Obtenemos el primer seguimiento del documento (cuando fue recepcionado localmente)
        $seguimiento = $this->documento->Seguimientos->first();

        $url = url( sprintf('panel/documentos/seguimiento?search=%d&read=1', $seguimiento->getKey()) );

        $subject = sprintf('Nueva recepción local :: %s %s',$this->documento->TipoDocumento->getNombre(), $this->documento->getNumero());

        $data = [
            'url' => $url,
            'tipo_recepcion' => 'Recepción local',
            'documento' => $this->documento
        ];

        $mail = $this->view('email.nuevoDocumentoRecepcionado')->with($data);
        $mail->subject($subject);
        $mail->attachData($pdf->Output(), $acuseRecepcion->getNombre(), ['mime' => 'application/pdf']);

        $escaneos = $this->documento->Escaneos()->with('Archivo')->limit(2)->get();

        foreach ($escaneos as $escaneo) {
            $path_archivo = $escaneo->Archivo->getPath();
            $path_archivo = storage_path( $path_archivo );
            $mail->attach($path_archivo, ['as' => $escaneo->getNombre()]);
        }

        return $mail;
    }
}
