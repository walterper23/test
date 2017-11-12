<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Presenters\MAnexoPresenter;

class MAnexo extends Model{
    
    protected $table          = 'cat_anexos';
    protected $primaryKey     = 'ANEX_ANEXO';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    
    /** Relationships **/



    /** ************ **/


    public function presenter(){
        return new MAnexoPresenter($this);
    }

}
