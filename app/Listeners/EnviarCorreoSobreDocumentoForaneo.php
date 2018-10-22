<?php
namespace App\Listeners;

use App\Events\NuevoDocumentoForaneoRecepcionado;
use App\Mail\MailNuevoDocumentoForaneoRecepcionado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EnviarCorreoSobreDocumentoForaneo /*implements ShouldQueue*/
{
    /**
     * Handle the event.
     *
     * @param  NuevoDocumentoForaneoRecepcionado  $event
     * @return void
     */
    public function handle(NuevoDocumentoForaneoRecepcionado $event)
    {
        $documento = $event->documento;
        $correos   = $event->correos;

        // Mandar notificación sobre documento foráneo
        Mail::to($correos)->send( new MailNuevoDocumentoForaneoRecepcionado($documento) );
    }
}