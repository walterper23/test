<?php
namespace App\Http\Controllers\Documento;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

/* Models */
use App\Model\MDocumento;
use App\Model\MDocumentoForaneo;
use App\Model\MEscaneo;

class DocumentoController extends BaseController
{
    public function __construct(){
        $this -> setLog('DocumentoController.log');
    }

    public function index(){

    }



    public function localAnexos(Request $request)
    {
        $documento = MDocumento::find( $request -> id );

        $data['title']    = sprintf('Anexos del documento #%s', $documento -> getCodigo() );
        $data['anexos']   = $this -> getAnexos( $documento );

        return view('Documento.modalAnexosEscaneos') -> with($data);
    }

    public function localEscaneos(Request $request)
    {
        $documento = MDocumento::find( $request -> id );

        $data['title']    = sprintf('Escaneos del documento #%s', $documento -> getCodigo() );
        $data['escaneos'] = $this -> getEscaneos( $documento );

        return view('Documento.modalAnexosEscaneos') -> with($data);
    }

    public function localArchivoEscaneo(Request $request)
    {

        $scan = $request -> get('scan');

        if (is_null($scan)) abort(404);

        $escaneo = MEscaneo::with('Archivo') -> find( $scan );

        $pathToFile = storage_path($escaneo -> Archivo -> getPath());

        return response() -> file($pathToFile, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="archivo.pdf"'
        ]);
    }

    public function localAnexosEscaneos(Request $request)
    {
        $documento = MDocumento::find( $request -> id );

        $data['title']    = sprintf('Anexos y escaneos del documento #%s', $documento -> getCodigo() );
        $data['anexos']   = $this -> getAnexos( $documento, 'col-md-6' );
        $data['escaneos'] = $this -> getEscaneos( $documento, 'col-md-6' );

        return view('Documento.modalAnexosEscaneos') -> with($data);

    }

    public function foraneoAnexos(Request $request)
    {

    }

    public function foraneoEscaneos(Request $request)
    {

    }

    public function foraneoAnexosEscaneos(Request $request)
    {
        
    }

    private function getAnexos( $documento, $size = '' )
    {
        $data['anexos'] = $documento -> Detalle -> getAnexos();

        if (! empty($size))
            $data['size'] = $size;

        return view('Documento.contenedorAnexos') -> with($data);
    }

    private function getEscaneos( $documento, $size = '' )
    {
        $data['escaneos'] = $documento -> Escaneos() -> with('Archivo') -> get();

        if (! empty($size))
            $data['size'] = $size;

        return view('Documento.contenedorEscaneos') -> with($data);
    }


}