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

    public function getID(){
    	return $this->attributes[ $this->primaryKey ];
    }

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

    public function getStatus(){
        $status = '';
        switch ( $this->getStatusID() ) {
            case 1:
                $status = '<span class="uk-badge uk-badge-primary">' . $this->status->getNombre() . '</span>';
                break;
            case 2:
                $status = '<span class="uk-badge uk-badge-warning">' . $this->status->getNombre() . '</span>';
                break;
            case 3:
                $status = '<span class="uk-badge uk-badge-warning">' . $this->status->getNombre() . '</span>';
                break;
            case 4:
                $status = '<span class="uk-badge uk-badge-warning">' . $this->status->getNombre() . '</span>';
                break;
            case 5:
                $status = '<span class="uk-badge uk-badge-danger">' . $this->status->getNombre() . '</span>';
                break;
            case 6:
                $status = '<span class="uk-badge uk-badge-success">' . $this->status->getNombre() . '</span>';
                break;
            default: break;
        }
        return $status;
    }

    public function status(){
        return $this->hasOne('App\Model\ModeloStatus', 'STAT_STATUS', 'DOCU_STATUS');
    }

    public function municipio(){
        return $this->hasOne('App\Model\ModeloMunicipio', 'MUNI_MUNICIPIO', 'DOCU_MUNICIPIO');
    }

}
