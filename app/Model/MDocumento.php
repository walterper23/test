<?php
namespace App\Model;

class MDocumento extends BaseModel
{
    protected $table        = 'documentos';
    protected $primaryKey   = 'DOCU_DOCUMENTO';
    protected $prefix       = 'DOCU';

    protected $casts = [
        'DOCU_IMPORTANTE' => 'array',
        'DOCU_ARCHIVADO'  => 'array',
    ];

    /* Methods */

    // Método para devolver la columna FOLIO del registro como un código de longitud indicada
    public function getFolio( $size = 3, $str = '0', $direction = STR_PAD_LEFT )
    {
        $longitud = config_var('Sistema.Longitud.Folio');
        
        if( $longitud > 0 ) {
            $size = $longitud;
        }

        return parent::getFolio($size,$str,$direction);
    }

    public function getDetalle()
    {
        return $this->getAttribute('DOCU_DETALLE');
    }

    public function getNumero()
    {
        return $this->getAttribute('DOCU_NUMERO_DOCUMENTO');
    }

    public function getEstadoDocumento()
    {
        return $this->getAttribute('DOCU_SYSTEM_ESTADO_DOCTO');
    }

    public function getTipoDocumento()
    {
        return $this->getAttribute('DOCU_SYSTEM_TIPO_DOCTO');
    }

    public function getTipoRecepcion()
    {
        return $this->getAttribute('DOCU_TIPO_RECEPCION');
    }

    public function isLocal()
    {
        return $this->getTipoRecepcion() == 1;
    }

    public function isForaneo()
    {
        return $this->getTipoRecepcion() == 2;
    }

    public function marcarImportante()
    {
        $lista_usuarios = $this->getAttribute('DOCU_IMPORTANTE'); // Recuperamos la lista de usuarios que han marcado el documento como importante
        $lista_usuarios = $this->marcarDocumento($lista_usuarios); // Procesamos la lista de usuarios
        $this->setAttribute('DOCU_IMPORTANTE',$lista_usuarios); // Guardamos la nueva lista de usuarios
    }

    public function importante()
    {
        $usuarios = $this->getAttribute('DOCU_IMPORTANTE'); // Recuperamos la lista de usuarios que han marcado como importante el documento
        $usuario = array_search(userKey(), $usuarios);
        
        return $usuario !== false; // Devolver si el usuario está en la lista
    }

    public function marcarArchivado()
    {
        $lista_usuarios = $this->getAttribute('DOCU_ARCHIVADO'); // Recuperamos la lista de usuarios que han marcado el documento como archivado
        $lista_usuarios = $this->marcarDocumento($lista_usuarios); // Procesamos la lista de usuarios
        $this->setAttribute('DOCU_ARCHIVADO',$lista_usuarios); // Guardamos la nueva lista de usuarios
    }

    public function archivado()
    {
        $usuarios = $this->getAttribute('DOCU_ARCHIVADO'); // Recuperamos la lista de usuarios que han archivado el documento
        $usuario = array_search(userKey(), $usuarios);

        return $usuario !== false; // Devolver si el usuario está en la lista
    }

    private function marcarDocumento( $usuarios )
    {
        $usuario = array_search(userKey(), $usuarios); // Índice

        if ($usuario === false){ // Si el usuario no está en la lista, lo añadimos
            $usuarios[] = userKey();
        } else { // Si ya está en la lista, lo quitamos
            unset($usuarios[ $usuario ]);
        }

        return $usuarios;
    }

    public function recepcionado()
    {
        return $this->getEstadoDocumento() == 2; // Documento recepcionado
    }

    public function enSeguimiento()
    {
        return $this->getEstadoDocumento() == 3; // Documento en seguimiento
    }

    public function finalizado()
    {
        return $this->getEstadoDocumento() == 4; // Documento finalizado
    }

    public function rechazado()
    {
        return $this->getEstadoDocumento() == 5; // Documento rechazado
    }

    /* Local Scopes */

    public function scopeGuardado($query)
    {
        return $query->where('DOCU_GUARDADO',1);
    }

    public function scopeNoGuardado($query)
    {
        return $query->where('DOCU_GUARDADO',0);
    }

    public function scopeIsDenuncia($query)
    {
        return $query->where('DOCU_SYSTEM_TIPO_DOCTO',1);
    }

    public function scopeIsDocumentoDenuncia($query)
    {
        return $query->where('DOCU_SYSTEM_TIPO_DOCTO',2);
    }

    public function scopeIsDocumentoGeneral($query)
    {
        return $query->whereNotIn('DOCU_SYSTEM_TIPO_DOCTO',[1,2]);
    }

    public function scopeIsLocal($query)
    {
        return $query->where('DOCU_TIPO_RECEPCION',1);
    }

    public function scopeIsForaneo($query)
    {
        return $query->where('DOCU_TIPO_RECEPCION',2);
    }


    /* Relationships */

    public function AcuseRecepcion()
    {
        return $this->hasOne('App\Model\MAcuseRecepcion','ACUS_DOCUMENTO')->where('ACUS_CAPTURA',1);
    }

    public function Denuncia()
    {
        return $this->hasOne('App\Model\MDenuncia','DENU_DOCUMENTO');
    }

    public function Detalle()
    {
        return $this->hasOne('App\Model\MDetalle','DETA_DETALLE','DOCU_DETALLE');
    }

    public function DocumentoDenuncia()
    {
        return $this->hasOne('App\Model\MDocumentoDenuncia','DODE_DOCUMENTO_LOCAL');
    }

    public function DocumentoForaneo()
    {
        return $this->hasOne('App\Model\MDocumentoForaneo','DOFO_DOCUMENTO_LOCAL');
    }

    public function Escaneos()
    {
        return $this->hasMany('App\Model\MEscaneo','ESCA_DOCUMENTO_LOCAL');
    }

    public function EstadoDocumento()
    {
        return $this->belongsTo('App\Model\System\MSystemEstadoDocumento','DOCU_SYSTEM_ESTADO_DOCTO');
    }

    public function Seguimientos()
    {
        return $this->hasMany('App\Model\MSeguimiento','SEGU_DOCUMENTO');
    }

    public function TipoDocumento()
    {
        return $this->belongsTo('App\Model\System\MSystemTipoDocumento','DOCU_SYSTEM_TIPO_DOCTO');
    }

}