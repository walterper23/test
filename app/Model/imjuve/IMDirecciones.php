<?php


namespace App\Model\imjuve;

use Illuminate\Database\Eloquent\Model;

class IMDirecciones extends Model
{
    protected $table        = 'm_direcciones';
    protected $primaryKey   = 'DIRE_ID ';
    protected $prefix       = 'DIRE';
    public $timestamps = false;
}
