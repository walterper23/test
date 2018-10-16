<?php
namespace App\Presenters;

class MUsuarioDetallePresenter extends Presenter
{
	public function getNombreCompleto()
	{
		return trim(sprintf('%s %s',$this->model->getNombres(),$this->model->getApellidos()));
	}

}