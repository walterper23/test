<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagerRecepcionRequest extends FormRequest
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
            'action'         => 'required|in:1,2,3,4',
            'tipo_documento' => 'required_if:action:2,3,4',
            'numero'         => 'required_if:action,1,2|min:1,max:255',
            'recepcion'      => 'required_if:action,1|date_format:Y-m-d',
            'municipio'      => 'required_if:action,1',
            'descripcion'    => 'required_if:action,1',
            'responsable'    => 'required_if:action,1',
        ];
    }

    public function messages(){
        return [
            'tipo_documento.required_if' => 'Seleccione el tipo de documento',
            'numero.required_if'         => 'Introduzca el número del documento',
            'recepcion.required_if'      => 'Introduzca la fecha de recepción',
            'recepcion.date_format'      => 'La fecha de recepción no es válida',
            'municipio.required_if'      => 'Seleccione un municipio',
            'descripcion.required_if'    => 'Introduzca el asunto o descripción',
            'responsable.required_if'    => 'Introduzca el nombre del responsable',
        ];
    }
}
