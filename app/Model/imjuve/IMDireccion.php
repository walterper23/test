<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;


class IMDireccion extends BaseModel
{
    protected $table       = 'm_direcciones';
    protected $primaryKey  = 'DIRE_ID';
    protected $prefix      = 'DIRE';
        public $timestamps = false;


    /* Methods */

    public function getCp()
    {
        return $this->getAttribute('DIRE_CP');
    }
    public function getEntidad()
    {
        return $this->getAttribute('DIRE_ENTI_ID');
    }

    public function getMunicipio()
    {
        return $this->getAttribute('DIRE_MUNI_ID');
    }
    public function getLocalidad()
    {
        return $this->getAttribute('DIRE_LOCA_ID');
    }

    public function getAsentamiento()
    {
        return $this->getAttribute('DIRE_ASEN_ID');
    }
    public function getTvialidad()
    {
        return $this->getAttribute('DIRE_TVIA_ID');
    }
    public function getVialidad()
    {
        return $this->getAttribute('DIRE_VIALIDAD');
    }
    public function getNext()
    {
        return $this->getAttribute('DIRE_NUM_EXTERIOR');
    }
    public function getNint()
    {
        return $this->getAttribute('DIRE_NUM_INTERIOR');
    }

    public function getDireccionCompleta()
    {
        return $this->getValidad().'  '.'#Ext '.$this->getNext().'  '.'#Int'.'  '.$this->getNint(). ' ' . $this->getCp();
    }
}