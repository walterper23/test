<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;

/* Presenter */
use App\Presenters\IMAfiliacionPresenter;

class IMAfiliacion extends BaseModel
{
    protected $table        = 'm_afiliados';
    protected $primaryKey   = 'AFIL_ID';
    protected $prefix       = 'AFIL';

    public function getFacebook()
    {
        return $this->getAttribute('AFIL_FACEBOOK');
    }
    public function getNombres()
    {
        return $this->getAttribute('AFIL_NOMBRES');
    }
    public function getPaterno()
    {
        return $this->getAttribute('AFIL_PATERNO');
    }
    public function getMaterno()
    {
        return $this->getAttribute('AFIL_MATERNO');
    }
    public function getGenero()
    {
        return $this->getAttribute('AFIL_GENE_ID');
    }
    public function getDireccion()
    {
        return $this->getAttribute('AFIL_DIRE_ID');
    }
    public function getFechaNacimiento()
    {
        return $this->getAttribute('AFIL_FECHA_NACIMIENTO');
    }
    public function getNacionalidad()
    {
        return $this->getAttribute('AFIL_FECHA_NACIMIENTO');
    }
    public function getEscolaridad()
    {
        return $this->getAttribute('AFIL_ESCO_ID');
    }
    public function getEcivil()
    {
        return $this->getAttribute('AFIL_ESCI_ID');
    }
    public function getOcupacion()
    {
        return $this->getAttribute('AFIL_OCUP_ID');
    }

    public function getNombreCompleto(){
        return $this->getNombres().' '.$this->getPaterno().' '.$this->getMaterno();
    }

    public function getTelefono(){
        return $this->getAttribute('AFIL_TELEFONO');
    }

    public function getCorreo(){
        return $this->getAttribute('AFIL_CORREO');
    }
    /* Relationships */
    public function Direccion()
    {
        return $this->belongsTo('App\Model\imjuve\IMDireccion','AFIL_DIRE_ID','DIRE_ID');
    }
    public function Genero()
    {
        return $this->hasOne('App\Model\imjuve\IMGenero','GENE_ID','AFIL_GENE_ID');
    }
    /* Presenter */

    public function presenter()
    {
        return new IMAfiliacionPresenter($this);
    }


}