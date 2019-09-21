<?php
namespace App\Model\System;

use App\Model\BaseModel;

class MSystemConfig extends BaseModel
{
    protected $table          = 'system_config';
    protected $primaryKey     = 'SYCO_SYSTEM_CONFIG';

    /* Methods */
    public function getNombre()
    {
        return $this->getAttribute('SYCO_NOMBRE');
    }

    public function getDescripcion()
    {
        return $this->getAttribute('SYCO_DESCRIPCION');
    }

    public function getVariable()
    {
        return $this->getAttribute('SYCO_VARIABLE');
    }

    public function getValor()
    {
        return $this->getAttribute('SYCO_VALOR');
    }

    public static function getAllVariables()
    {
        return cache('System.Config.Variables');
    }

    public static function setAllVariables()
    {
        //cache()->forget('System.Config.Variables');

        $all = cache()->rememberForever('System.Config.Variables',function(){
            return self::all();
        });

        //cache()->forget('System.Config.Variables.Array');

        cache()->rememberForever('System.Config.Variables.Array',function($all){
            return $all->pluck('SYCO_VALOR','SYCO_VARIABLE')->toArray();
        });
    }

    /* Relationships */

}