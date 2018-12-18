<?php
namespace App\Http\Controllers\Documento;

use Illuminate\Http\Request;

/* Controllers */
use App\Http\Controllers\BaseController; 

/* Models */
use App\Model\MDocumento;
use App\Model\MDocumentoForaneo;
use App\Model\MEscaneo;

class DocumentoController extends BaseController
{
    public function __construct(){
        $this->setLog('DocumentoController.log');
    }

    public function index(){

    }


    public function local()
    {

    }

    public function localAnexos(Request $request)
    {
        $documento               = MDocumento::find( $request->id );
        $data['title']           = sprintf('Anexos del documento #%s', $documento->getFolio() );
        $data['anexos']          = $this->getAnexos( $documento );
        $data['folio_recepcion'] = $documento->AcuseRecepcion->getNumero();


        return view('Documento.modalAnexosEscaneos')->with($data);
    }

    public function localEscaneos(Request $request)
    {
        $documento = MDocumento::find( $request->id );

        $data['title']           = sprintf('Escaneos del documento #%s', $documento->getFolio() );
        $data['escaneos']        = $this->getEscaneos( $documento );
        $data['folio_recepcion'] = $documento->AcuseRecepcion->getNumero();

        return view('Documento.modalAnexosEscaneos')->with($data);
    }

    public function localArchivoEscaneo(Request $request)
    {
        $scan = $request->get('scan');

        if (is_null($scan)) abort(404);

        $escaneo = MEscaneo::with('Archivo')->findOrFail( $scan );

        $pathToFile = storage_path($escaneo->Archivo->getPath());

        return response()->file($pathToFile, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('inline; filename="%s.pdf"', $escaneo->getNombre())
        ]);
    }

    public function localAnexosEscaneos(Request $request)
    {
        $documento = MDocumento::find( $request->id );

        $data['title']           = sprintf('Anexos y escaneos: %s #%s', $documento->TipoDocumento->getNombre(), $documento->getFolio() );
        $data['anexos']          = $this->getAnexos( $documento, 'col-md-5' );
        $data['escaneos']        = $this->getEscaneos( $documento, 'col-md-7' );
        $data['folio_recepcion'] = $documento->AcuseRecepcion->getNumero();

        return view('Documento.modalAnexosEscaneos')->with($data);
    }

    public function foraneo()
    {

    }

    public function foraneoAnexos(Request $request)
    {

    }

    public function foraneoEscaneos(Request $request)
    {

    }

    public function foraneoAnexosEscaneos(Request $request)
    {
        $documento = MDocumentoForaneo::find( $request->id );

        $data['title']           = sprintf('Anexos y escaneos: %s #%s', $documento->TipoDocumento->getNombre(), $documento->getFolio());
        $data['anexos']          = $this->getAnexos( $documento, 'col-md-6' );
        $data['escaneos']        = $this->getEscaneos( $documento, 'col-md-6' );
        $data['folio_recepcion'] = $documento->AcuseRecepcion->getNumero();

        return view('Documento.modalAnexosEscaneos')->with($data);
    }

    private function getAnexos( $documento, $size = '' )
    {
        $data['anexos'] = $documento->Detalle->presenter()->getAnexos();

        if (! empty($size))
            $data['size'] = $size;

        return view('Documento.contenedorAnexos')->with($data);
    }

    private function getEscaneos( $documento, $size = '' )
    {
        $data['escaneos'] = $documento->Escaneos()->with('Archivo')->get();

        if (! empty($size))
            $data['size'] = $size;

        return view('Documento.contenedorEscaneos')->with($data);
    }

}