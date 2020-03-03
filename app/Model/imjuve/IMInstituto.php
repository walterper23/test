<?php

namespace App\Model\imjuve;

use App\Model\BaseModelTrait;
use Illuminate\Database\Eloquent\Model;

class IMInstituto extends Model
{
    use BaseModelTrait;

    protected $table        = 'm_organismos';
    protected $primaryKey   = 'ORGA_ID';
    protected $prefix       = 'ORGA';
    public $timestamps = false;

    /*METODOS*/

    public function getRazonSocial()
    {
        return $this->getAttribute('ORGA_RAZON_SOCIAL');
    }

    public function getAlias()
    {
        return $this->getAttribute('ORGA_ALIAS');
    }
    public function getTelefono()
    {
        return $this->getAttribute('ORGA_TELEFONO');
    }
    public function getAvatarSmall()
    {
        return $this->getAttribute('ORGA_FOTO_SMALL ');
    }

    public function getAvatarFull()
    {
        return $this->getAttribute('ORGA_FOTO_FULL ');
    }
    
  

    
    /* Relationships */

    public function Direccion()
    {

        return $this->belongsTo('App\Model\imjuve\IMDireccion','ORGA_DIRE_ID','DIRE_ID');
    }

   

}
