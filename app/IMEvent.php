<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



/* Presenter */
//use App\Presenters\MDepartamentoPresenter;


class IMEvent extends Model
{
    protected $table        = 'm_eventos';
    protected $primaryKey   = 'EVEN_ID';
    protected $prefix       = 'EVEN';

    /* Relationships */
    public function Direccion()
    {
        return $this->belongsTo('App\Model\imjuve\IMDireccion','EVEN_DIRE_ID','DIRE_ID');
    }

    public function getFechaEvento()
    {
        return $this->getAttribute('EVEN_FECHA');
    }

    
   
}
