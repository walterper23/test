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

    // Función para añadir el ID del usuario a la lista de usuarios que han leido el Seguimiento
    public function marcarComoLeido()
    {
        $lecturas = $this -> attributes['SEGU_LEIDO']; // Recuperamos la lista de usuarios que han leido el seguimiento

        if (! strpos($lecturas,userKey())) // Si el usuario no ha leido el seguimiento ...
        {
            if (! empty($lecturas)) // Si ya hay usuarios que han leido el Seguimiento, añadimos una coma
            {
                $lecturas .= ',';
            }

            $this -> attributes['SEGU_LEIDO'] = $lecturas . userKey(); // ... añadimos al usuario a la lista
            $this -> save();
        }
    }


    /* Relationships */

    public function Direccion()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDireccion','SEGU_DIRECCION','DIRE_DIRECCION');
    }

    public function Departamento()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDepartamento','SEGU_DEPARTAMENTO','DEPA_DEPARTAMENTO');
    }

    public function Documento()
    {
        return $this -> belongsTo('App\Model\MDocumento','SEGU_DOCUMENTO','DOCU_DOCUMENTO');
    }

    public function Usuario()
    {
        return $this -> belongsTo('App\Model\MUsuario','SEGU_USUARIO','USUA_USUARIO');
    }

    public function EstadoDocumento()
    {
        return $this -> belongsTo('App\Model\Catalogo\MEstadoDocumento','SEGU_ESTADO_DOCUMENTO','ESDO_ESTADO_DOCUMENTO');
    }

    /* Presenter */

    public function presenter()
    {
        return new MSeguimientoPresenter($this);
    }

}