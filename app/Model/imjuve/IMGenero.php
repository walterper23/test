<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;
use App\Presenters\IMGeneroPresenter;


class IMGenero extends BaseModel
{
    protected $table       = 'c_generos';
    protected $primaryKey  = 'GENE_ID';
    protected $prefix      = 'GENE';

    /* Methods */
    public function getNombre()
    {
    	return $this->getAttribute('GENE_NOMBRE');
    }

    public function getIcon(){
        return $this->getAttribute('GENE_ICON');
    }

    public static function getAll($estatus=1)
    {
        $all = self::where('GENE_ENABLED',$estatus);
        return $all;
    }

    public static function getSelect()
    {
        $estatus = 1;
        return self::getAll($estatus)->pluck('GENE_NOMBRE','GENE_ID')->toArray();
    }

    /* Presenter */
    public function presenter()
    {
        return new IMGeneroPresenter($this);
    }
}