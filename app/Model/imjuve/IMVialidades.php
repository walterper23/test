<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;

class IMVialidades extends BaseModel
{
    protected $table       = 'c_tipos_vialidades';
    protected $primaryKey  = 'tipo_vialidad_id';

    /* Methods */
    public function getNombre()
    {
        return $this->getAttribute('tipo_vialidad_nombre');
    }

    public static function getSelect()
    {
        return self::pluck('tipo_vialidad_nombre','tipo_vialidad_id')->toArray();
    }
}