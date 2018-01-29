<?php

namespace App\Model\Catalogo;

use Illuminate\Support\Facades\DB;
use Auth;

use App\Model\BaseModel;

use App\Presenters\MTipoDocumentoPresenter;

class MTipoDocumento extends BaseModel {
    
    protected $table          = 'cat_tipos_documentos';
    protected $primaryKey     = 'TIDO_TIPO_DOCUMENTO';
    public    $timestamps     = false;

    protected $fieldCreatedBy = 'TIDO_CREATED_BY';
    protected $fieldUpdated   = 'TIDO_UPDATED';

    protected $fillable = [];

    protected $hidden = [];

    public function getCodigo(){
    	return str_pad(self::getKey(), 3, '0', STR_PAD_LEFT);
    }

    public function getNombre(){
    	return $this->attributes['TIDO_NOMBRE_TIPO'];
    }



    /** Relationships **/
    public function documentos(){
        return $this->hasMany('App\Model\MDocumento','DOCU_TIPO_DOCUMENTO',$this->primaryKey);
    }



    /** ************ **/

    public function presenter(){
        return new MTipoDocumentoPresenter($this);
    }

}
