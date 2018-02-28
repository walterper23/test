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
                'required_if:action,1,2',
                \Illuminate\Validation\Rule::unique('usuarios')->where(function($query){
                    return $query -> where('USUA_DELETED',0);
                }),
                'min:1,max:255',
            ],
            'password'    => 'required_if:action,1,2,5|confirmed|min:6,max:255',
            'descripcion' => 'required_if:action,1,2|min:1,max:255',
            'nombres'     => 'required_if:action,1,2|min:1,max:255',
            'apellidos'   => 'required_if:action,1,2|min:1,max:255',
            'email'       => 'required_if:action,1,2|email|min:5,max:255',
            'telefono'    => 'min:1,max:25',
        ];
    }

    public function messages(){
        return [
            'action.required'     => 'Petición no especificada',
            'action.in'           => 'Petición no válida',
            'usuario.required_if' => 'Introduzca el nombre de usuario',
            'usuario.min'         => 'Mínimo :min caracter',
            'usuario.max'         => 'Máximo :max caracteres',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            'nombres.required'    => 'Introduzca el nombre(s) del usuario',
            'nombres.min'         => 'Mínimo :min caracter',
            'nombres.max'         => 'Máximo :max caracteres',
            'apellidos.required'  => 'Introduzca el apellido(s) del usuario',
            'apellidos.min'       => 'Mínimo :min caracter',
            'apellidos.max'       => 'Máximo :max caracteres',
            'apellidos.required'  => 'Introduzca el apellido(s) del usuario',
            'apellidos.min'       => 'Mínimo :min caracter',
            'apellidos.max'       => 'Máximo :max caracteres',

        ];
    }
}