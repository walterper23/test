<?php
namespace App\Listeners;

use App\Events\NuevoDocumentoLocalRecepcionado;
use App\Mail\MailNuevoDocumentoLocalRecepcionado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EnviarCorreoSobreDocumentoLocal /*implements ShouldQueue*/
{
    /**
     * Handle the event.
     *
     * @param  NuevoDocumentoLocalRecepcionado  $event
     * @return void
     */
    public function handle(NuevoDocumentoLocalRecepcionado $event)
    {
        $documento = $event->documento;
        $correos   = $event->correos;

        // Mandar notificación sobre documento local
        Mail::to($correos)->send( new MailNuevoDocumentoLocalRecepcionado($documento) );
    }
}
