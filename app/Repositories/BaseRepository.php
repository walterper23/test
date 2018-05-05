<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository implements InterfaceRepository
{
	protected $model;
	protected $record;
	protected $records;

	public function __construct(Model $model)
	{
		$this -> model = $model;
	}

	// Método para buscar un registro por ID
	public function find($id)
	{
		$this -> record = $this -> model -> find($id);
		return $this;
	}

	// Método para buscar un registro por ID o lanzar una excepción si no es encontrado
	public function findForce($id)
	{
		$this -> record = $this -> model -> findOrFail($id);
		return $this;
	}

	// Método para agregar el ID del usuario a una lista de usuarios
	public function markUser()
	{

	}

	// Método para quitar el ID del usuario de una lista de usuarios
	public function unmarkUser()
	{

	}

	// Método para agregar o quitar el ID del usuario en una lista de usuarios
	public function toggleMarkUser()
	{

	}

	public function toArray()
	{

	}

	/* Creacion, actualizacion de registro */

	public function newRecord()
	{

	}

	public function updateRecord()
	{
		
	}


	// Método para guardar el modelo
	public function save()
	{
		return $this -> model -> save();
	}

}