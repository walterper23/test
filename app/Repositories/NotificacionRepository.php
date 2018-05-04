<?php
namespace App\Repositories;

use App\Model\MNotificacion;

class NotificacionRepository extends BaseRepository
{
	public function __construct(MNotificacion $model)
	{
		parent::__construct($model);
	}


}