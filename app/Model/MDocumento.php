<?php
namespace App\Model;

class MDocumento extends BaseModel
{
    protected $table        = 'documentos';
    protected $primaryKey   = 'DOCU_DOCUMENTO';
    protected $prefix       = 'DOCU';

    /* Methods */

    public function getNumero()
    {
        return $this -> attributes['DOCU_NUMERO_DOCUMENTO'];
    }

    public function getEstadoDocumento()
    {
        return $this -> attributes['DOCU_SYSTEM_ESTADO_DOCTO'];
    }

    public function getTipoDocumento()
    {
        return $this -> attributes['DOCU_SYSTEM_TIPO_DOCTO'];
    }

    public function getTipoRecepcion()
    {
        return $this -> attributes['DOCU_TIPO_RECEPCION'];
    }

    public function isLocal()
    {
        return $this -> getTipoRecepcion() == 1;
    }

    public function isForaneo()
    {
        return $this -> getTipoRecepcion() == 2;
    }

    public function marcarImportante()
    {
        $lista_usuarios = $this -> attributes['DOCU_IMPORTANTE']; // Recuperamos la lista de usuarios que han marcado el documento como importante
        $lista_usuarios = $this -> marcarDocumento($lista_usuarios); // Procesamos la lista de usuarios
        $this -> attributes['DOCU_IMPORTANTE'] = $lista_usuarios; // Guardamos la nueva lista de usuarios
    }

    public function importante()
    {
        $usuarios = $this -> attributes['DOCU_IMPORTANTE']; // Recuperamos la lista de usuarios que han marcado como importante el documento
        $lista = explode(',', $usuarios);
        $usuario = array_search(userKey(), $lista);
        return $usuario !== false; // Devolver si el usuario está en la lista
    }

    public function marcarArchivado()
    {
        $lista_usuarios = $this -> attributes['DOCU_ARCHIVADO']; // Recuperamos la lista de usuarios que han marcado el documento como archivado
        $lista_usuarios = $this -> marcarDocumento($lista_usuarios); // Procesamos la lista de usuarios
        $this -> attributes['DOCU_ARCHIVADO'] = $lista_usuarios; // Guardamos la nueva lista de usuarios
    }

    public function archivado()
    {
        $usuarios = $this -> attributes['DOCU_ARCHIVADO']; // Recuperamos la lista de usuarios que han archivado el documento
        $lista = explode(',', $usuarios);
        $usuario = array_search(userKey(), $lista);
        return $usuario !== false; // Devolver si el usuario está en la lista
    }

    private function marcarDocumento( $usuarios )
    {
        $lista = [];

        if (! empty(trim($usuarios)))
            $lista = explode(',', $usuarios);

        $usuario = array_search(userKey(), $lista);

        if ($usuario === false) // Si el usuario no está en la lista, lo añadimos
        {
            $lista[] = userKey();
        }
        else // Si ya está en la lista, lo quitamos
        {
            unset($lista[ $usuario ]);
        }

        $lista = implode(',', $lista);

        return $lista;
    }

    public function recepcionado()
    {
        return $this -> attributes['DOCU_SYSTEM_ESTADO_DOCTO'] == 2; // Documento recepcionado
    }

    public function enSeguimiento()
    {
        return $this -> attributes['DOCU_SYSTEM_ESTADO_DOCTO'] == 3; // Documento en seguimiento
    }

    public function finalizado()
    {
        return $this -> attributes['DOCU_SYSTEM_ESTADO_DOCTO'] == 4; // Documento finalizado
    }

    public function rechazado()
    {
        return $this -> attributes['DOCU_SYSTEM_ESTADO_DOCTO'] == 5; // Documento rechazado
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

    public function AcuseRecepcion()
    {
        return $this -> hasOne('App\Model\MAcuseRecepcion','ACUS_DOCUMENTO',$this -> getKeyName()) -> where('ACUS_CAPTURA',1);
    }

    public function Denuncia()
    {
        return $this -> hasOne('App\Model\MDenuncia','DENU_DOCUMENTO',$this -> getKeyName());
    }

    public function Detalle()
    {
        return $this -> hasOne('App\Model\MDetalle','DETA_DETALLE','DOCU_DETALLE');
    }

    public function DocumentoDenuncia()
    {
        return $this -> belongsTo('App\Model\MDocumentoDenuncia',$this -> getKeyName(),'DODE_DOCUMENTO_LOCAL');
    }

    public function DocumentoForaneo()
    {
        return $this -> hasOne('App\Model\MDocumentoForaneo','DOFO_DOCUMENTO_LOCAL',$this -> getKeyName());
    }

    public function Escaneos()
    {
        return $this -> hasMany('App\Model\MEscaneo','ESCA_DOCUMENTO_LOCAL',$this -> getKeyName());
    }

    public function EstadoDocumento()
    {
        return $this -> hasOne('App\Model\System\MSystemEstadoDocumento','SYED_ESTADO_DOCUMENTO','DOCU_SYSTEM_ESTADO_DOCTO');
    }

    public function Seguimientos()
    {
        return $this -> hasMany('App\Model\MSeguimiento','SEGU_DOCUMENTO',$this -> getKeyName());
    }

    public function TipoDocumento()
    {
        return $this -> hasOne('App\Model\System\MSystemTipoDocumento','SYTD_TIPO_DOCUMENTO','DOCU_SYSTEM_TIPO_DOCTO');
    }

}