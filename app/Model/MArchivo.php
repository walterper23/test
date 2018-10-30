<?php
namespace App\Model;

/* Presenter */
use App\Presenters\MArchivoPresenter;

class MArchivo extends BaseModel
{
	protected $table      = 'archivos';
	protected $primaryKey = 'ARCH_ARCHIVO';
	protected $prefix     = 'ARCH';
	
	/* Methods */

	public function getFilename()
	{
		return $this->getAttribute('ARCH_FILENAME');
	}

	public function getFolder()
	{
		return $this->getAttribute('ARCH_FOLDER');
	}

	public function getSize()
	{
		return $this->getAttribute('ARCH_SIZE');
	}

	public function getPath()
	{
		return $this->getAttribute('ARCH_PATH');
	}

	/* Presenter */

	public function presenter()
	{
		return new MArchivoPresenter($this);
	}

}