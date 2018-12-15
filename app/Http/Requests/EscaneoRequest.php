<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EscaneoRequest extends FormRequest
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
            'tipo'      => 'required|in:local,foraneo',
            'documento' => 'required',
            // 'escaneo'   => 'required|max:3072|mimes:pdf',
            'escaneo'   => 'required|max:10072',
        ];
    }

    public function messages(){
        return [
            'tipo.required'      => 'Tipo de documento no especificado',
            'tipo.in'            => 'El tipo de documento no es válido',
            'documento.required' => 'El documento no ha sido especificado',
            'escaneo.required'   => 'El archivo no ha sido especificado',
            'escaneo.max'        => 'El tamaño del archivo excede de :max kb (3 Mb)',
            'escaneo.mimes'      => 'Formato de archivo no válido',
        ];
    }
}
