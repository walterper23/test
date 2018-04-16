<?php
namespace App\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait BaseModelTrait
{
    // Método para recuperar el nombre de los campos con prefijo
    public function getField( $field )
    {
        return sprintf('%s_%s',$this -> prefix, $field);
    }

    // Método para recuperar las columnas de una tabla
    public function getColumnsTable()
    {
        $nameTableCache = sprintf('ColumnsTable.%s',$this -> getTable());

        $columns = Cache::rememberForever($nameTableCache,function(){
            return array_map('strtolower',$this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable()));
        });

        return $columns;
    }   

    // Método para saber si existe el campo en los atributos de la instancia
    public function existsAttribute( $field )
    {
        return array_key_exists($field, $this -> attributes) ? true : in_array(strtolower($field), $this -> getColumnsTable());
    }

    // Método para guardar la información del usuario que ha creado el registro
    protected function creatingRegister()
    {
        $fieldCreatedAt = $this -> getField('CREATED_AT');
        if ($this -> existsAttribute($fieldCreatedAt))
            $this -> attributes[ $fieldCreatedAt ] = Carbon::now(); // Guardar la fecha de creación

        $fielCreatedBy = $this -> getField('CREATED_BY');
        if ($this -> existsAttribute($fielCreatedBy))
            $this -> attributes[ $fielCreatedBy ] = json_encode(['k' => userKey(),'u' => user() -> getAuthUsername()]); // Información del usuario
    }

    // Método para guardar la información del usuario que esta haciendo cambios en el registro
    private function updatingRegister(){
        $fieldUpdated = $this -> getField('UPDATED');

        if ($this -> existsAttribute($fieldUpdated))
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

    // Método para devolver el ID del registro como un código de longitud indicada
    public function getCodigo( $size = 3, $str = '0', $direction = STR_PAD_LEFT)
    {
        return str_pad($this -> getKey(), $size, $str, $direction);
    }

    // Método para marcar como activo o disponible el registro
    public function activar()
    {
        $fieldEnabled = $this -> getField('ENABLED');
        if ($this -> existsAttribute($fieldEnabled))
            $this -> attributes[ $fieldEnabled ] = 1;
        return $this;
    }

    // Método para saber si el registro está disponible
    public function disponible()
    {   
        $fieldEnabled = $this -> getField('ENABLED');
        if ($this -> existsAttribute($fieldEnabled))
            return ($this -> attributes[ $fieldEnabled ] == 1);
        return false;
    }

    // Método para marcar como activo o disponible el registro
    public function desactivar()
    {
        $fieldEnabled = $this -> getField('ENABLED');
        if ($this -> existsAttribute($fieldEnabled))
            $this -> attributes[ $fieldEnabled ] = 0;
        return $this;
    }

    // Método para cambiar la disponibilidad actual del registro al contrario
    public function cambiarDisponibilidad()
    {
        $fieldEnabled = $this -> getField('ENABLED');
        if ($this -> existsAttribute($fieldEnabled))
            $this -> attributes[ $fieldEnabled ] = $this -> attributes[ $fieldEnabled ] * -1 + 1;

        return $this;
    }

    // Método para marcar el registro como eliminado
    public function eliminar()
    {   
        $fieldDeleted = $this -> getField('DELETED');
        if ($this -> existsAttribute($fieldDeleted))
            $this -> attributes[ $fieldDeleted ] = 1; // Marcar el registro como eliminado

        $fieldDeletedAt = $this -> getField('DELETED_AT');
        if ($this -> existsAttribute($fieldDeletedAt))
            $this -> attributes[ $fieldDeletedAt ] = Carbon::now(); // Colocar la fecha de eliminación

        $fieldDeletedBy = $this -> getField('DELETED_BY');
        if ($this -> existsAttribute($fieldDeletedBy))
            $this -> attributes[ $fieldDeletedBy ] = json_encode(['k' => user() -> getKey(),'u' => user() -> getAuthUsername()]);

        return $this;
    }

    // Método para saber si el registro está eliminado
    public function eliminado()
    {
        $fieldDeleted = $this -> getField('DELETED');
        if ($this -> existsAttribute($fieldDeleted))
            return ($this -> attributes[ $fieldDeleted ] == 1);
        return true;
    }

    // Método para quitar la marca de eliminado del registro
    public function restituir()
    {
        $fieldDeleted = $this -> getField('DELETED');
        if ($this -> existsAttribute($fieldDeleted))
            $this -> attributes[ $fieldDeleted ] = 0;
        return $this;
    }

    // Scope para incluir solamente los registros que tengan la marca de Disponible
    public function scopeDisponible($query)
    {
        $fieldEnabled = $this -> getField('ENABLED');
        if ($this -> existsAttribute($fieldEnabled))
            return $query -> where($fieldEnabled,1);
        return $query;
    }

    // Scope para incluir solamente los registros que no tengan la marca de Eliminado
    public function scopeExistente($query)
    {
        $fieldDeleted = $this -> getField('DELETED');
        if ($this -> existsAttribute($fieldDeleted))
            return $query -> where($fieldDeleted,0);
        return $query;
    }

    // Scope para incluir solamente los registros que no tengan la marca de Eliminado y que tengan la marca de Disponible
    public function scopeExistenteDisponible($query)
    {
        return $query -> where(function($q){
            $q -> existente() -> disponible();
        });
    }

}