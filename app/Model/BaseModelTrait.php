<?php
namespace App\Model;

use Carbon\Carbon;

trait BaseModelTrait
{

    // Método para recuperar el nombre de los campos con prefijo
    public function getField( $field ){
        return sprintf('%s_%s',$this -> prefix, $field);
    }

    // Método para devolver el ID del registro como un código de longitud indicada
    public function getCodigo( $size = 3, $str = '0', $direction = STR_PAD_LEFT)
    {
        return str_pad($this -> getKey(), $size, $str, $direction);
    }

    // Método para cambiar la disponibilidad del registro
    public function cambiarDisponibilidad()
    {
        $fieldEnabled = $this -> getField('ENABLED');
        if (! is_null($fieldEnabled))
        {
            $this -> attributes[ $fieldEnabled ] = $this -> attributes[ $fieldEnabled ] * -1 + 1;
        }

        return $this;
    }

    // Método para saber si el registro está disponible
    public function disponible()
    {   
        $fieldEnabled = $this -> getField('ENABLED');
        if (! is_null($fieldEnabled))
            return ($this -> attributes[ $fieldEnabled ] == 1);
        return false;
    }

    // Método para marcar el registro como eliminado
    public function eliminar()
    {   
        $fieldDeleted = $this -> getField('DELETED');
        if (! is_null($fieldDeleted))
        {
            $this -> attributes[ $fieldDeleted ] = 1; // Marcar como eliminado el registro
        }

        $fieldDeletedAt = $this -> getField('DELETED_AT');
        if (! is_null($fieldDeletedAt))
        {
            $this -> attributes[ $fieldDeletedAt ] = Carbon::now(); // Colocar la fecha de eliminación
        }

        $fieldDeletedBy = $this -> getField('DELETED_BY');
        if (! is_null($fieldDeletedBy))
        {
            $this -> attributes[ $fieldDeletedBy ] = json_encode(['k' => user() -> getKey(),'u' => user() -> getAuthUsername()]);
        }

        return $this;
    }

    // Método para saber si el registro está eliminado
    public function eliminado()
    {
        $fieldDeleted = $this -> getField('DELETED');
        if (! is_null($fieldDeleted))
            return ($this -> attributes[ $fieldDeleted ] == 1);
        return true;
    }

}