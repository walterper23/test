<?php
namespace App\Presenters;

class MUsuarioDetallePresenter extends Presenter{

	


	public function nombreCompleto(){
		return trim(sprintf('%s %s',$this -> model -> getNombres(),$this -> model -> getApellidos()));
	}

}