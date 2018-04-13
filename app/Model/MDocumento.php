<?php
namespace App\Model;

class MDocumento extends BaseModel
{
    protected $table        = 'documentos';
    protected $primaryKey   = 'DOCU_DOCUMENTO';
    protected $prefix       = 'DOCU';

    public function getNumero()
    {
        return $this -> attributes['DOCU_NUMERO_DOCUMENTO'];
    }

    /* Methods */

    public function marcarImportante()
    {
        $lista_usuarios = $this -> attributes['DOCU_IMPORTANTE']; // Recuperamos la lista de usuarios que han marcado el documento como importante
        $lista_usuarios = $this -> marcarDocumento($lista_usuarios); // Procesamos la lista de usuarios
        $this -> attributes['DOCU_IMPORTANTE'] = $lista_usuarios; // Guardamos la nueva lista de usuarios
    }

    public function importante()
    {
        return (strpos($this -> attributes['DOCU_IMPORTANTE'],strval(userKey())) !== false);
    }

    public function marcarArchivado()
    {
        $lista_usuarios = $this -> attributes['DOCU_ARCHIVADO']; // Recuperamos la lista de usuarios que han marcado el documento como archivado
        $lista_usuarios = $this -> marcarDocumento($lista_usuarios); // Procesamos la lista de usuarios
        $this -> attributes['DOCU_ARCHIVADO'] = $lista_usuarios; // Guardamos la nueva lista de usuarios
    }

    public function archivado()
    {
        return (strpos($this -> attributes['DOCU_ARCHIVADO'],strval(userKey())) !== false);
    }

    private function marcarDocumento( $lista_usuarios )
    {
        if (empty($lista_usuarios)) // Si la lista de usuarios está vacía, añadimos al usuario a la lista
        {
            $lista_usuarios = userKey();
        }
        else if (! (strpos($lista_usuarios,strval(userKey())) !== false)) // Si el usuario no ha marcado el documento, lo añadimos a la lista después de una coma
        {
            $lista_usuarios .= ',' . userKey();
        }
        else // Si ya lo tiene marcado, quitaremos al usuario de la lista
        {
            $lista_usuarios = str_replace(userKey(), '', $lista_usuarios); // Reemplazar el ID del usuario por vacío
            $lista_usuarios = str_replace(',,', ',', $lista_usuarios); // Eliminar dos comas seguidas, por sólo una coma

            if (! empty($lista_usuarios))
            {
                if( $lista_usuarios[0] == ',' ) // Si quedó una coma al inicio ...
                    $lista_usuarios = substr($lista_usuarios, 1); // ... eliminar la coma del inicio

                if( $lista_usuarios[-1] == ',' ) // Si quedó una coma al final ...
                    $lista_usuarios = substr($lista_usuarios, 0, -1); // ... eliminar la coma del final
            }
            else
            {
                $lista_usuarios = null;
            }
        }

        return $lista_usuarios;
    }

    public function resuelto()
    {
        return $this -> attributes['DOCU_SYSTEM_ESTADO_DOCTO'] == 4; // Documento resuelto
    }

    /* Local Scopes */

    public function scopeGuardado($query)
    {
        return $query -> where('DOCU_GUARDADO',1);
    }

    public function scopeNoGuardado($query)
    {
        return $query -> where('DOCU_GUARDADO',0);
    }


    /* Relationships */

    public function Denuncia()
    {
        return $this -> hasOne('App\Model\MDenuncia','DENU_DOCUMENTO',$this -> getKeyName());
    }

    public function Detalle()
    {
        return $this -> hasOne('App\Model\MDetalle','DETA_DETALLE','DOCU_DETALLE');
    }

    public function EstadoDocumento()
    {
        return $this -> hasOne('App\Model\Sistema\MSistemaEstadoDocumento','SYED_ESTADO_DOCUMENTO','DOCU_SYSTEM_ESTADO_DOCTO');
    }

    public function Seguimientos()
    {
        return $this -> hasMany('App\Model\MSeguimiento','SEGU_DOCUMENTO',$this -> getKeyName());
    }

    public function TipoDocumento()
    {
        return $this -> hasOne('App\Model\Sistema\MSistemaTipoDocumento','SYTD_TIPO_DOCUMENTO','DOCU_SYSTEM_TIPO_DOCTO');
    }

}