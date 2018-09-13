<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NumeroOficioRequerido;

class RecepcionLocalRequest extends FormRequest
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
            'action'         => 'required|in:0,1,2,3,4',
            'id'             => 'required_if:action:2,3,4',
            'tipo_documento' => 'required_if:action:0,1,2|exists:system_tipos_documentos,SYTD_TIPO_DOCUMENTO',
            'numero'         => [ new NumeroOficioRequerido ],
            'recepcion'      => 'required_if:action,1|date_format:Y-m-d',
            'municipio'      => 'required_if:action,1',
            'denuncia'       => 'required_if:tipo_documento,2',
            'descripcion'    => 'required_if:action,1',
            'responsable'    => 'required_if:action,1',
        ];
    }

    public function messages(){
        return [
            'action.required'            => 'Petición no especificada',
            'action.in'                  => 'Petición no válida',
            'id.required_if'             => 'Especifique el identificador del recurso',
            'tipo_documento.required_if' => 'Seleccione el tipo de documento',
            'tipo_documento.exists'      => 'Tipo de Documento no encontrado',
            'recepcion.required_if'      => 'Introduzca la fecha de recepción',
            'recepcion.date_format'      => 'La fecha de recepción no es válida',
            'municipio.required_if'      => 'Seleccione un municipio',
            'denuncia.required_if'       => 'Seleccione el expediente donde se agregará el presente documento',
            'descripcion.required_if'    => 'Introduzca el asunto o descripción',
            'responsable.required_if'    => 'Introduzca el nombre del responsable',
        ];
    }
}
