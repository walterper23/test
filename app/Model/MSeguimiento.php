<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Presenters\MSeguimientoPresenter;

class MSeguimiento extends BaseModel
{
    protected $table       = 'seguimiento';
    protected $primaryKey  = 'SEGU_SEGUIMIENTO';

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
        $lecturas = $this -> attributes['SEGU_LEIDO']; // Recuperamos la lista de usuarios que han leido el seguimiento

        if (! strpos($lecturas,strval(userKey()))) // Si el usuario no ha leido el seguimiento ...
        {
            if (! empty($lecturas)) // Si ya hay usuarios que han leido el seguimiento, añadimos una coma
            {
                $lecturas .= ',';
            }

            $this -> attributes['SEGU_LEIDO'] = $lecturas . userKey(); // ... añadimos al usuario a la lista
            $this -> save();
        }
    }

    public function seguimientoLeido()
    {
        $lecturas = $this -> attributes['SEGU_LEIDO']; // Recuperamos la lista de usuarios que han leido el seguimiento
        return strpos($lecturas,strval(userKey())) !== false; // Devolver si el usuario ha leido el seguimiento
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

    public function EstadoDocumento()
    {
        return $this -> belongsTo('App\Model\Catalogo\MEstadoDocumento','SEGU_ESTADO_DOCUMENTO','ESDO_ESTADO_DOCUMENTO');
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