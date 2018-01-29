<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {

	protected $fieldCreatedBy;
	protected $fieldUpdated;

	protected static function boot(){

        parent::boot();

        static::creating(function($model){
        	$model->fieldCreatedBy();
        });

        static::updating(function($model){
        	$model->fieldUpdated();
        });

    }

    private function fieldCreatedBy(){
        if( !is_null($this->fieldCreatedBy) ){
        	$user = \Auth::user();
    		$this->attributes[ $this->fieldCreatedBy ] = json_encode(['id'=>$user->getKey(),'usuario'=>$user->getAuthUsername()]);
	    }
    }

    /* Método aun no terminado */
    private function fieldUpdated(){
	    if( !is_null($this->fieldUpdated) ){
            
            $updated = $this->attributes[ $this->fieldUpdated ] ?? json_encode([]);

        	$user = \Auth::user();
        	$info = ['id'=>$user->getKey(),'usuario'=>$user->getAuthUsername()];
            
            array_push($info,json_decode($updated,true));

            $this->attributes[ $this->fieldUpdated ] = json_encode( $updated );
	    }
    }

}