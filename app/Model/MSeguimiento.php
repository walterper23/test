<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Presenters\MSeguimientoPresenter;

class MSeguimiento extends BaseModel
{
    protected $table       = 'seguimiento';
    protected $primaryKey  = 'SEGU_SEGUIMIENTO';
    protected $prefix      = 'SEGU';

    /* Methods */

    public function getInstruccion()
    {
        return $this -> attributes['SEGU_INSTRUCCION'];
    }

    public function getObservacion()
    {
        return $this -> attributes['SEGU_OBSERVACION'];
    }

    // Función para añadir el ID del usuario a la lista de usuarios que han leido el Seguimiento
    public function marcarComoLeido()
    {
        $usuarios = $this -> attributes['SEGU_LEIDO']; // Recuperamos la lista de usuarios que han leido el seguimiento
        
        $lista = [];

        if (! empty(trim($usuarios)))
            $lista = explode(',', $usuarios);

        $usuario = array_search(userKey(), $lista);

        if ($usuario === false) // Si el usuario no está en la lista, lo añadimos
            $lista[] = userKey();

        $lista = implode(',', $lista);

        $this -> attributes['SEGU_LEIDO'] = $lista; // ... añadimos al usuario a la lista
        
        return $this;
    }

    public function leido()
    {
        $usuarios = $this -> attributes['SEGU_LEIDO']; // Recuperamos la lista de usuarios que han leido el seguimiento

        $lista = explode(',', $usuarios);

        $usuario = array_search(userKey(), $lista);

        return $usuario !== false; // Devolver si el usuario está en la lista
    }


    /* Relationships */

    public function DireccionOrigen()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDireccion','SEGU_DIRECCION_ORIGEN','DIRE_DIRECCION');
    }

    public function DepartamentoOrigen()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDepartamento','SEGU_DEPARTAMENTO_ORIGEN','DEPA_DEPARTAMENTO');
    }

    public function DireccionDestino()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDireccion','SEGU_DIRECCION_DESTINO','DIRE_DIRECCION');
    }

    public function DepartamentoDestino()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDepartamento','SEGU_DEPARTAMENTO_DESTINO','DEPA_DEPARTAMENTO');
    }

    public function Documento()
    {
        return $this -> belongsTo('App\Model\MDocumento','SEGU_DOCUMENTO','DOCU_DOCUMENTO');
    }

    public function Escaneos()
    {
        return $this -> hasMany('App\Model\MEscaneo','ESCA_DOCUMENTO_LOCAL','SEGU_DOCUMENTO');
    }

    public function EstadoDocumento()
    {
        return $this -> belongsTo('App\Model\Catalogo\MEstadoDocumento','SEGU_ESTADO_DOCUMENTO','ESDO_ESTADO_DOCUMENTO');
    }

    public function Seguimientos()
    {
        return $this -> hasMany('App\Model\MSeguimiento','SEGU_DOCUMENTO','SEGU_DOCUMENTO');
    }
    
    public function Usuario()
    {
        return $this -> belongsTo('App\Model\MUsuario','SEGU_USUARIO','USUA_USUARIO');
    }


    /* Presenter */

    public function presenter()
    {
        return new MSeguimientoPresenter($this);
    }

}