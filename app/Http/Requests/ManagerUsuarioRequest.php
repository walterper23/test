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
            'action'      => 'required|in:1,2,3,4,5',
            'id'          => 'required_if:action:2,3,4,5',
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

    public function messages(){
        return [
            'action.required'         => 'Petición no especificada',
            'action.in'               => 'Petición no válida',
            'usuario.unique'          => 'Ya existe el usuario <b>:input</b>',
            'usuario.required_if'     => 'Introduzca el nombre de usuario',
            'usuario.min'             => 'Mínimo :min caracter',
            'usuario.max'             => 'Máximo :max caracteres',
            'password.required_if'    => 'Introduzca una contraseña',
            'password.min'            => 'Mínimo :min caracteres',
            'password.max'            => 'Máximo :max caracteres',
            'password.confirmed'      => 'Las contraseñas no coinciden',
            'notrabajador.max'        => 'Máximo :max caracteres',
            'descripcion.required_if' => 'Introduzca una descripción',
            'descripcion.min'         => 'Mínimo :min caracteres',
            'descripcion.max'         => 'Máximo :max caracteres',
            'nombres.required_if'     => 'Introduzca el nombre(s) del usuario',
            'nombres.min'             => 'Mínimo :min caracter',
            'nombres.max'             => 'Máximo :max caracteres',
            'apellidos.required_if'   => 'Introduzca el apellido(s) del usuario',
            'apellidos.min'           => 'Mínimo :min caracter',
            'apellidos.max'           => 'Máximo :max caracteres',
            'genero.required_if'      => 'Seleccione un género',
            'genero.in'               => 'Género no válido',
            'email.required_if'       => 'Introduzca el correo electrónico del usuario',
            'email.min'               => 'Mínimo :min caracteres',
            'email.max'               => 'Máximo :max caracteres',
            'email.email'             => 'Introduzca un correo electrónico válido',
            'telefono.min'            => 'Mínimo :min caracteres',
            'telefono.max'            => 'Máximo :max caracteres',
        ];
    }
}