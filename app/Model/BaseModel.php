<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BaseModel extends Model
{
    use BaseModelTrait;

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
        	$model -> creatingRegister();
        });

        /*
        static::updating(function($model){
        	$model -> updatingRegister();
        });
        */
    }

}