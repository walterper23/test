<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstitutoRequest extends FormRequest
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
           /*
            'nombre' => 'required_if:action,1,2|min:1,max:255',
            'organismo'    => 'required_if:action,1,2|min:1,max:255',
            'razon'    => 'required_if:action,1,2|min:1,max:255',
            'telefono'    => 'required_if:action,1,2|min:1,max:255',
         */  ];
    }

    public function messages(){
        return [
            /*
            'organismo.required_if' => 'Introduzca un nombre de organismo',
            'organismo.min'      => 'Mínimo :min caracter',
            'organismo.max'      => 'Máximo :max caracteres',
            'razon.required_if' => 'Introduzca una razon social',
            'razon.min'      => 'Mínimo :min caracter',
            'razon.max'      => 'Máximo :max caracteres',
            'telefono.required_if' => 'Introduzca un numero telefonico',
            'telefono.min'      => 'Mínimo :min caracter',
            'telefono.max'      => 'Máximo :max caracteres',
       */  ];
    }
}
