<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {

    use BaseModelTrait;

    public $timestamps = false;

	protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
        	//$model -> fieldCreatedBy();
        });

        static::updating(function($model){
        	//$model -> fieldUpdated();
        });
    }

    private function fieldCreatedBy()
    {
        if (isset($this -> fieldCreatedBy))
        {
    		$this -> attributes[ $this -> fieldCreatedBy ] = json_encode(['id' => user() -> getKey(),'usuario' => user() -> getAuthUsername()]);
	    }
    }

    /* Método aun no terminado */
    private function fieldUpdated(){
	    if (isset($this -> fieldUpdated))
        {
            $updated = $this -> attributes[ $this -> fieldUpdated ] ?? json_encode([]);

        	$info = ['id' => user() -> getKey(),'usuario' => user() -> getAuthUsername()];
            
            array_push($info,json_decode($updated,true));

            $this -> attributes[ $this -> fieldUpdated ] = json_encode( $updated );
	    }
    }

}