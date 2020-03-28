<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagerActividadRequest extends FormRequest
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
            'action'      => 'required|in:1,2,3,4,5,6,7,8,9,10,11,12',
            'id'          => 'required_if:action:2,3,4,5,6,7,8,9,10,11,12',                      
            'descripcion'   => 'required_if:action,1,2|min:3,max:255',
            'nombre'       => 'required_if:action,1,2|min:1,max:255',           
            'tipoActividad'        => 'required_if:action,1,2,3,4,5,6,7,8,9,10,11,12|in:1,2,3,4,5,6,7,8,9,10,11,12',           
        ];
    }

    public function messages(){
        return [
            'action.required'         => 'Petición no especificada',
            'action.in'               => 'Petición no válida',
            'id.required_if'          => 'Especifique el identificador del recurso',            
            'descripcion.required_if' => 'Introduzca una descripción',
            'descripcion.min'         => 'Mínimo :min caracteres',
            'descripcion.max'         => 'Máximo :max caracteres',
            'nombre.required_if'     => 'Introduzca el nombre(s) del usuario',
            'nombre.min'             => 'Mínimo :min caracter',           
            'tipoActividad.required_if'      => 'Seleccione un género',
            'tipoActividad.in'               => 'Género no válido',
           
        ];
    }
}