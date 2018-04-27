<?php
namespace App\Presenters;

class MArchivoPresenter extends Presenter {

	
	public function getSize( $type = 'Kb' )
	{
		$size = $this -> model -> getSize();

		switch ($type) {
			case 'Kb':
				$size = number_format($size / 1000, 2);
				return sprintf('%s %s',$size, $type);
			case 'Mb':
				$size = number_format($size / 1000000, 2);
				return sprintf('%s %s',$size, $type);
				break;
			
			default:
				return $this -> getSize();
		}

	}


}