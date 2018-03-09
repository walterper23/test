<?php
namespace App\Model;

use Carbon\Carbon;

trait BaseModelTrait {

    public function getCodigo( $size = 3, $str = '0', $direction = STR_PAD_LEFT ){
        return str_pad($this -> attributes[ $this -> getKeyName() ], $size, $str, $direction);
    }

    public function disponible(){
        if( isset($this -> fieldEnabled) )
            return ($this -> attributes[ $this -> fieldEnabled ] == 1);
        return false;
    }

    public function cambiarDisponibilidad(){
        if( isset($this -> fieldEnabled) ){
            $this -> attributes[ $this -> fieldEnabled ] = $this -> attributes[ $this -> fieldEnabled ] * -1 + 1;
        }
        return $this;
    }

    public function eliminar(){
        if( isset($this -> fieldDeleted) ){
            $this -> attributes[ $this -> fieldDeleted ] = 1;

            $fieldDeletedAt = sprintf('%s_AT',$this->fieldDeleted);
            $fieldDeletedBy = sprintf('%s_BY',$this->fieldDeleted);

            $usuario = \Auth::user();

            if( array_key_exists($fieldDeletedAt, $this -> attributes) ){
                $this -> attributes[ $fieldDeletedAt ] = Carbon::now();
            }

            if( array_key_exists($fieldDeletedBy, $this -> attributes) ){
                $this -> attributes[ $fieldDeletedBy ] = json_encode(['id' => $usuario -> getKey(),'username' => $usuario -> getAuthUsername()]);
                
            }
        }

        return $this;
    }

}