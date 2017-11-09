<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MSystemLog extends Model{
    
    protected $table          = 'system_logs';
    protected $primaryKey     = 'SYLO_SYSTEM_LOG';
    public    $timestamps     = false;

    protected $fillable = [
    ];

    protected $hidden = [
    ];

    public function getID(){
    	return $this->attributes[ $this->primaryKey ];
    }


}
