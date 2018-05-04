<?php
namespace App\Repositories;

use App\Model\MUsuario;

class UsuarioRepository extends BaseRepository
{
	public function __construct(MUsuario $model)
	{
		parent::__construct($model);
	}


}