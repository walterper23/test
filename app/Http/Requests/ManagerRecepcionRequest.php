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
            'tipo_documento' => 'required_if:action:2,3,4|exists:system_tipos_documentos,SYTD_TIPO_DOCUMENTO',
            'numero'         => 'required_if:action,1,2|min:1,max:255',
            'recepcion'      => 'required_if:action,1|date_format:Y-m-d',
            'municipio'      => 'required_if:action,1',
            'descripcion'    => 'required_if:action,1',
            'responsable'    => 'required_if:action,1',
        ];
    }

    public function messages(){
        return [
            'action.required'            => 'Petición no especificada',
            'action.in'                  => 'Petición no válida',
            'tipo_documento.required_if' => 'Seleccione el tipo de documento',
            'tipo_documento.exists'      => 'Tipo de Documento no encontrado',
            'numero.required_if'         => 'Introduzca el número del documento',
            'recepcion.required_if'      => 'Introduzca la fecha de recepción',
            'recepcion.date_format'      => 'La fecha de recepción no es válida',
            'municipio.required_if'      => 'Seleccione un municipio',
            'descripcion.required_if'    => 'Introduzca el asunto o descripción',
            'responsable.required_if'    => 'Introduzca el nombre del responsable',
        ];
    }
}
