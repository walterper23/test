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
            'documento' => 'required',
            'escaneo'   => 'required|max:3072|mimes:pdf',
        ];
    }

    public function messages(){
        return [
            'documento.required' => 'El documento no ha sido especificado',
            'escaneo.required'   => 'El archivo no ha sido especificado',
            'escaneo.max'        => 'El tamaño del archivo excede de :max kb (3 Mb)',
            'escaneo.mimes'      => 'Formato de archivo no válido',
        ];
    }
}
