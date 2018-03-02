<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {

	protected $fieldCreatedBy;
	protected $fieldUpdated;
    protected $fieldEnabled;
    protected $fieldCode;

    public function __construct(){
        parent::__construct();
        $this -> fieldCode = $this -> getKeyName();
    }

	protected static function boot(){

        parent::boot();

        static::creating(function($model){
        	$model -> fieldCreatedBy();
        });

        static::updating(function($model){
        	$model -> fieldUpdated();
        });

    }

    private function fieldCreatedBy(){
        if( !is_null($this -> fieldCreatedBy) ){
        	$user = \Auth::user();
    		$this -> attributes[ $this -> fieldCreatedBy ] = json_encode(['id' => $user -> getKey(),'usuario' => $user -> getAuthUsername()]);
	    }
    }

    /* Método aun no terminado */
    private function fieldUpdated(){
	    if( !is_null($this -> fieldUpdated) ){
            
            $updated = $this -> attributes[ $this -> fieldUpdated ] ?? json_encode([]);

        	$user = \Auth::user();
        	$info = ['id' => $user -> getKey(),'usuario' => $user -> getAuthUsername()];
            
            array_push($info,json_decode($updated,true));

            $this -> attributes[ $this -> fieldUpdated ] = json_encode( $updated );
	    }
    }

    public function getCodigo( $size = 3, $str = '0', $direction = STR_PAD_LEFT ){
        if( !is_null($this -> fieldCode) )
            return str_pad($this -> attributes[ $this -> fieldCode ], $size, $str, $direction);
        return '';
    }

    public function disponible(){
        if( !is_null($this -> fieldEnabled) )
            return ($this -> attributes[ $this -> fieldEnabled ] == 1);
        return false;
    }

    public function cambiarDisponibilidad(){
        if( !is_null($this -> fieldEnabled) ){
            $this -> attributes[ $this -> fieldEnabled ] = $this -> attributes[ $this -> fieldEnabled ] * -1 + 1;
        }
        return $this;
    }

}