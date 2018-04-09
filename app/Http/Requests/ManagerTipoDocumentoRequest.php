<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagerTipoDocumentoRequest extends FormRequest
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
            'action' => 'required|in:1,2,3,4',
            'id'     => 'required_if:action:2,3,4',
            'nombre' => 'required_if:action,2|min:1,max:255'
        ];
    }

    public function messages(){
        return [
            'action.required'    => 'Petición no especificada',
            'action.in'          => 'Petición no válida',
            'nombre.required_if' => 'Introduzca un nombre',
            'nombre.min'         => 'Mínimo :min caracter',
            'nombre.max'         => 'Máximo :max caracteres'
        ];
    }
}
