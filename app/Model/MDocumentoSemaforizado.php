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
        return $this -> attributes['DOSE_ESTADO'];
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
        return $this -> attributes['DOSE_FECHA_LIMITE'];
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