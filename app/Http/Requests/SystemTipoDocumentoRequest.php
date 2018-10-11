<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemTipoDocumentoRequest extends FormRequest
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
            'action'   => 'required|in:1,2,3,4,5',
            'id'       => 'required_if:action:2,3,4,5',
            'nombre'   => 'required_if:action:1,2|min:1,max:100',
            'etiqueta' => 'max:100',
            'color'    => 'required_if:action:1,2',
        ];
    }

    public function messages(){
        return [
            'action.required'      => 'Petición no especificada',
            'action.in'            => 'Petición no válida',
            'id.required_if'       => 'Especifique el identificador del recurso',
            'nombre.required_if'   => 'Introduzca un nombre',
            'nombre.min'           => 'Mínimo :min caracter',
            'nombre.max'           => 'Máximo :max caracteres',
            'etiqueta.max'         => 'Máximo :max caracteres',
            'color.required_if'    => 'Seleccione un color',
        ];
    }
}
