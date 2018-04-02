<?php
namespace App\Model;

/* Models */
use Illuminate\Database\Eloquent\Model;

class MMArcador extends Model
{
    
	protected $table        = 'documentos_marcadores';
	protected $primaryKey   = 'DOMA_MARCADOR';
	public    $timestamps   = false;
	

	/* Methods */

	public function marcarImportante()
	{
		$usuarios = $this -> attributes['DOMA_IMPORTANTE']; // Recuperamos la lista de usuarios que han marcado el documento como importante

        if (empty($usuarios)) // Si la lista de usuarios está vacía, añadimos al usuario a la lista
        {
            $usuarios = userKey();
        }
        else if (! (strpos($usuarios,strval(userKey())) !== false)) // Si el usuario no ha marcado el documento, lo añadimos a la lista después de una coma
        {
        	$usuarios .= ',' . userKey();
        }
		else // Si ya lo tiene marcado, quitaremos al usuario de la lista
        {
        	$usuarios = str_replace(userKey(), '', $usuarios); // Reemplazar el ID del usuario por vacío
			$usuarios = str_replace(',,', ',', $usuarios); // Eliminar dos comas seguidas, por sólo una coma

			if (! empty($usuarios))
			{
				if( $usuarios[0] == ',' ) // Si quedó una coma al inicio ...
					$usuarios = substr($usuarios, 1); // ... eliminar la coma del inicio

				if( $usuarios[-1] == ',' ) // Si quedó una coma al final ...
					$usuarios = substr($usuarios, 0, -1); // ... eliminar la coma del final
			}
			else
			{
				$usuarios = null;
			}
        }
        
        $this -> attributes['DOMA_IMPORTANTE'] = $usuarios; // Guardamos la nueva lista de usuarios
	}

	/* Relationships */

	public function Documento()
	{
		return $this -> belongsTo('App\Model\MDocumento','DOMA_DOCUMENTO','DOCU_DOCUMENTO');
	}

}