<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BaseModel extends Model
{
    use BaseModelTrait;

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
        	$model -> fieldCreated();
        });

        static::updating(function($model){
        	$model -> fieldUpdated();
        });
    }

    // Método para guardar la información del usuario que ha creado el registro
    private function fieldCreated()
    {
        $fieldCreatedAt = $this -> getField('CREATED_AT');
        if (! is_null($fieldCreatedAt))
        {
            $this -> attributes[ $fieldCreatedAt ] = Carbon::now(); // Guardar la fecha de creación
        }

        $fielCreatedBy = $this -> getField('CREATED_BY');
        if (! is_null($fielCreatedBy))
        {
    		$this -> attributes[ $fielCreatedBy ] = json_encode(['k' => userKey(),'u' => user() -> getAuthUsername()]); // Información del usuario
	    }
    }

    // Método para guardar la información del usuario que esta haciendo cambios en el registro
    private function fieldUpdated(){
        $fieldUpdated = $this -> getField('UPDATED');

	    if (! is_null($fieldUpdated))
        {
            $updated = $this -> attributes[ $fieldUpdated ];

            if (empty($updated) || is_null($updated))
                $updated = json_encode([]);

            $updated = json_decode($updated, true);

        	$info = ['k' => userKey(),'u' => user() -> getAuthUsername()]; // ID y nombre de usuario

            array_push($updated,$info);

            $this -> attributes[ $fieldUpdated ] = json_encode( $updated );
	    }
    }

}