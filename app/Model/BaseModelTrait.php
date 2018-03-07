<?php
namespace App\Model;

trait BaseModelTrait {

    public function getCodigo( $size = 3, $str = '0', $direction = STR_PAD_LEFT ){
        $this -> fieldCode = $this -> getKeyName();
        if( isset($this -> fieldCode) )
            return str_pad($this -> attributes[ $this -> fieldCode ], $size, $str, $direction);
        return '';
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

}