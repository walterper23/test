<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MDocumento extends Model{
    
    protected $table          = 'documentos';
    protected $primaryKey     = 'DOCU_DOCUMENTO';
    public    $timestamps     = false;

    protected $fillable = [
    ];

    protected $hidden = [
    ];

    public function getStatusID(){
        return $this->attributes['DOCU_STATUS'];
    }

    public function getNumeroFicha(){
    	return $this->attributes['DOCU_NUMERO_FICHA'];
    }

    public function getNumeroOficio(){
    	return $this->attributes['DOCU_NUMERO_OFICIO'];
    }

    public function getAnio(){
        return $this->attributes['DOCU_ANIO'];
    }

    public function getFechaRecepcion(){
        return $this->attributes['DOCU_FECHA_RECEPCION'];
    }

    public function getFechaDireccion(){
        return $this->attributes['DOCU_FECHA_DIRECCION'];
    }

    public function getFechaCreacion(){
        return $this->attributes['DOCU_CREATED_AT'];
    }

    public function getDescripcion(){
        return $this->attributes['DOCU_DESCRIPCION'];
    }

    public function getAdjuntos(){
        return $this->attributes['DOCU_ADJUNTOS'];
    }

    public function getInstruccion(){
        return $this->attributes['DOCU_INSTRUCCION'];
    }

    public function getResponsable(){
        return $this->attributes['DOCU_RESPONSABLE'];
    }

    public function getRespuesta(){
        return $this->attributes['DOCU_RESPUESTA'];
    }

    public function getRecibio(){
        return $this->attributes['DOCU_RECIBIO'];
    }

    public function status(){
        return $this->hasOne('App\Model\MStatus', 'STAT_STATUS', 'DOCU_STATUS');
    }

    public function municipio(){
        return $this->hasOne('App\Model\MMunicipio', 'MUNI_MUNICIPIO', 'DOCU_MUNICIPIO');
    }

    public function documentoDetalle(){
        return $this->haOne('App\Model\MDocumentoDetalle','DODE_DOCUMENTO',$this->primaryKey);
    }

    public function seguimientos(){
        return $this->hasMany('App\Model\MSeguimiento','SEGU_DOCUMENTO',$this->getKeyName());
    }

}
