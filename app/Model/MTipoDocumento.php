<?php

namespace App\Model\Catalogo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Presenters\MTipoDocumentoPresenter;

class MTipoDocumento extends Model{
    
    protected $table          = 'cat_tipos_documentos';
    protected $primaryKey     = 'TIDO_TIPO_DOCUMENTO';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    public function getCodigo(){
    	return str_pad(self::getKey(), 3, '0', STR_PAD_LEFT);
    }

    public function getNombre(){
    	return $this->attributes['TIDO_NOMBRE_TIPO'];
    }


    public function presenter(){
        return new MTipoDocumentoPresenter($this);
    }

}
