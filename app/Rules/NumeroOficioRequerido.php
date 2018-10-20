<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NumeroOficioRequerido implements Rule
{
    protected $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $action         = request('action');
        $tipo_documento = request('tipo_documento');
        $numero         = request('numero');
        
        // Si la acción es Nueva o Editar y el tipo de documento no es Denuncia
        if ( in_array($action, [1,2]) && $tipo_documento != 1 )
        {
            $this->message = 'Introduzca el número del documento';
            return false;
            
            if ( strlen($numero) == 0 )
            {
                $this->message = 'Mínimo 1 caracter';
                return false;
            }

            if ( strlen($numero) > 255 )
            {
                $this->message = 'Máximo 255 caracteres';
                return false;
            }
        }


        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
