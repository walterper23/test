<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AfiliacionRequest extends FormRequest
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
            'action'    => 'required|in:1,2,3,4,5',
            'id'        => 'required_if:action:2,3,4,5',
            'nombres'    => 'required_if:action,1,2|min:1,max:255',
            'paterno'    => 'required_if:action,1,2|min:1,max:255',
            'materno'    => 'required_if:action,1,2|min:1,max:255',
            'genero'    => 'required_if:action,1,2',
            'nacimiento'    => 'required_if:action,1,2',
        ];
    }

    public function messages(){
        return [
            'action.required'       => 'Petición no especificada',
            'action.in'             => 'Petición no válida',
            'id.required_if'        => 'Especifique el identificador de la edición',
            'nombre.required_if'    => 'Introduzca un nombre',
            'nombre.min'            => 'Mínimo :min caracter',
            'nombre.max'            => 'Máximo :max caracteres',
            'paterno.required_if'    => 'Introduzca el apellido paterno',
            'paterno.min'            => 'Mínimo :min caracter',
            'paterno.max'            => 'Máximo :max caracteres',
            'materno.required_if'    => 'Introduzca el apellido materno',
            'materno.min'            => 'Mínimo :min caracter',
            'materno.max'            => 'Máximo :max caracteres',
            'genero.required_if'    => 'Introduzca el genero',
            'nacimiento.required_if'    => 'Introduzca la fecha de nacimiento',

        ];
    }
}
