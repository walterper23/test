<?php

namespace App\Presenters;

class MUsuarioPresenter extends Presenter{

	

	public function imgAvatarSmall($class = 'img-avatar' ){
		return "<img src='/img/avatars/{$this->model->getAvatarSmall()}' alt='' class='{$class}' title='{$this->model->usuarioDetalle->presenter()->nombreCompleto()}'>";
	}

}