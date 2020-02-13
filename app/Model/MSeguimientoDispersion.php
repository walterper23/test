<?php
namespace App\Model;

class MSeguimientoDispersion extends BaseModel
{
    protected $table       = 'seguimiento_dispersion';
    protected $primaryKey  = 'SEDI_DISPERSION';
    protected $prefix      = 'SEDI';

    /* Methods */

    public function getCodigo( $size = 5, $str = '0', $direction = STR_PAD_LEFT )
    {
        return parent::getCodigo($size);
    }

    public function getFechaFinalizacion()
    {
        return $this -> getAttribute('SEDI_FECHA_FINALIZACION');
    }

    /* Relationships */

    public function Direccion()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDireccion','SEDI_DIRECCION','DIRE_DIRECCION');
    }

    public function Departamento()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDepartamento','SEDI_DEPARTAMENTO','DEPA_DEPARTAMENTO');
    }

    public function Documento()
    {
        return $this -> belongsTo('App\Model\MDocumento','SEDI_DOCUMENTO','DOCU_DOCUMENTO');
    }

    public function Seguimiento()
    {
        return $this -> belongsTo('App\Model\MSeguimiento','SEDI_SEGUIMIENTO','SEGU_SEGUIMIENTO');
    }

    public function TipoDocumento()
    {
        return $this -> belongsTo('App\Model\System\MSystemTipoDocumento','SEDI_SYSTEM_TIPO_DOCTO','SYTD_TIPO_DOCUMENTO');
    }
    
    public function Usuario()
    {
        return $this -> belongsTo('App\Model\MUsuario','SEDI_USUARIO_FINALIZO','USUA_USUARIO');
    }

}