<?php
namespace App\Model\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MSystemLog extends Model
{
    protected $table          = 'system_logs';
    protected $primaryKey     = 'SYLO_SYSTEM_LOG';
    public    $timestamps     = false;

}