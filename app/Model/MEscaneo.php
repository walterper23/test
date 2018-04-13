<?php
namespace App\Model;

class MEscaneo extends BaseModel
{
    protected $table        = 'escaneos';
    protected $primaryKey   = 'ESCA_ESCANEO';
    protected $prefix       = 'ESCA';

    /* Methods */

    public function getNombre()
    {
        return $this -> attributes['ESCA_NOMBRE'];
    }

    public function getDescripcion()
    {
        return $this -> attributes['ESCA_DESCRIPCION'];
    }
    

    /* Relationships */
    
    public function Documento()
    {
       return $this -> belongsTo('App\Model\MDocumento','ESCA_DOCUMENTO','DOCU_DOCUMENTO');
    }

    public function Archivo()
    {
       return $this -> hasOne('App\Model\MArchivo','ARCH_ARCHIVO','ESCA_ARCHIVO');
    }

}