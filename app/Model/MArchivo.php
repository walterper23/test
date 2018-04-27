<?php
namespace App\Model;

/* Presenter */
use App\Presenters\MArchivoPresenter;

class MArchivo extends BaseModel
{
	protected $table        = 'archivos';
	protected $primaryKey   = 'ARCH_ARCHIVO';
	protected $prefix       = 'ARCH';
	

	/* Methods */

	public function getSize()
	{
		return $this -> attributes['ARCH_SIZE'];
	}

	public function getPath()
	{
		return $this -> attributes['ARCH_PATH'];
	}

	/* Presenter */

	public function presenter()
	{
		return new MArchivoPresenter($this);
	}

}