<?php
namespace App\Presenters;

class MUsuarioDetallePresenter extends Presenter{

	


	public function nombreCompleto(){
		return trim(sprintf('%s %s',$this -> model -> USDE_NOMBRES,$this -> model -> USDE_APELLIDOS));
	}

}