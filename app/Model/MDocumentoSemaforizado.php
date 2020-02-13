<?php
namespace App\Model;

class MDocumentoSemaforizado extends BaseModel
{
    protected $table        = 'documentos_semaforizados';
    protected $primaryKey   = 'DOSE_SEMAFORO';
    protected $prefix       = 'DOSE';

    /* Methods */

    public function getEstado()
    {
        return $this -> getAttribute('DOSE_ESTADO');
    }

    public function enEspera(){
        return $this -> getEstado() == 1;
    }

    public function noAtendido(){
        return $this -> getEstado() == 2;
    }

    public function respondido(){
        return $this -> getEstado() == 3;
    }

    public function getFechaLimite()
    {
        return $this -> getAttribute('DOSE_FECHA_LIMITE');
    }

    public function getSolicitud()
    {
        return $this -> getAttribute('DOSE_SOLICITUD');
    }

    public function getRespuesta()
    {
        return $this -> getAttribute('DOSE_RESPUESTA');
    }

    public function getRespuestaFecha()
    {
        return $this -> getAttribute('DOSE_RESPUESTA_FECHA');
    }


    /* Relationships */

    public function Documento()
    {
        return $this -> belongsTo('App\Model\MDocumento','DOSE_DOCUMENTO','DOCU_DOCUMENTO');
    }

    public function Usuario()
    {
        return $this -> belongsTo('App\Model\MUsuario','DOSE_USUARIO','USUA_USUARIO');
    }

    public function SeguimientoA()
    {
        return $this -> hasOne('App\Model\MSeguimiento','SEGU_SEGUIMIENTO','DOSE_SEGUIMIENTO_A');
    }

    public function SeguimientoB()
    {
        return $this -> hasOne('App\Model\MSeguimiento','SEGU_SEGUIMIENTO','DOSE_SEGUIMIENTO_B');
    }

}