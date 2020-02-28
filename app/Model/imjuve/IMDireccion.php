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
    public function getvialidad()
    {
        return $this->getAttribute('DIRE_VIALIDAD');
    }
    public function getext()
    {
        return $this->getAttribute('DIRE_NUM_EXTERIOR');
    }
    public function getint()
    {
        return $this->getAttribute('DIRE_NUM_INTERIOR');
    }


    public function getDireccionCompleta()
    {
       return $this->getvialidad().'  '.'#Ext '.$this->getext().'  '.'#Int'.'  '.$this->getint(). ' ' . $this->getCp();
    }

}