<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;

/* Presenter */
//use App\Presenters\MDepartamentoPresenter;

class IMAfiliacion extends BaseModel
{
    protected $table        = 'm_afiliados';
    protected $primaryKey   = 'AFIL_ID';
    protected $prefix       = 'AFIL';

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

    public function getFechaNacimiento()
    {
        return $this->getAttribute('AFIL_FECHA_NACIMIENTO');
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
    /*
    public function Puestos()
    {
        return $this->hasMany('App\Model\Catalogo\MPuesto','PUES_DEPARTAMENTO',$this->getKeyName());
    }*/


}