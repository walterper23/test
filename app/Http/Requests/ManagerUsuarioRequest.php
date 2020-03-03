<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagerUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'action'      => 'required|in:1,2,3,4,5,6',
            'id'          => 'required_if:action:2,3,4,5,6',
            'usuario'     => [
                'required_if:action,1',
                \Illuminate\Validation\Rule::unique('usuarios','USUA_USERNAME') -> where(function($query){
                    return $query -> where('USUA_DELETED',0);
                }),
                'min:1,max:255',
            ],
            'password'      => 'required_if:action,1,5|min:6,max:20|confirmed',
            'notrabajador'  => 'nullable|max:10',
            'descripcion'   => 'required_if:action,1,2|min:3,max:255',
            'nombres'       => 'required_if:action,1,2|min:1,max:255',
            'apellidos'     => 'required_if:action,1,2|min:1,max:255',
            'genero'        => 'required_if:action,1,2|in:HOMBRE,MUJER',
            'email'         => 'required_if:action,1,2|min:5,max:255|email',
            'telefono'      => 'nullable|min:1,max:25',
        ];
    }

   
}