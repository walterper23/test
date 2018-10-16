<?php
namespace App\Presenters;

class MUsuarioPresenter extends Presenter {

	

	public function imgAvatarSmall($class = 'img-avatar' ){
		return sprintf('<img src="/img/avatars/%s" alt="" class="%s" title="%s">',
					$this->model->getAvatarSmall(), $class, $this->model->UsuarioDetalle->presenter()->getNombreCompleto());
	}

}