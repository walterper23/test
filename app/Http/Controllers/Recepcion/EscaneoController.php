<?php
namespace App\Http\Controllers\Recepcion;

use Illuminate\Http\Request;
use App\Http\Requests\EscaneoRequest;
use Exception;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController; 

/* Models */
use App\Model\MDocumento;
use App\Model\MDocumentoForaneo;
use App\Model\MEscaneo;
use App\Model\MArchivo;

class EscaneoController extends BaseController
{
    public function nuevoEscaneo(EscaneoRequest $request)
    {
        try{
            $id_documento   = $request->get('documento');
            $escaneo_file   = $request->file('escaneo',null);

            if( is_null($escaneo_file) )
            {
                return $this->responseErrorJSON('Archivo no válido');
            }

            $documento = MDocumento::findOrFail($id_documento);
            
            if( $documento->isLocal() ) // Documento local
            {
                $filename        = 'docto_%d_scan_%d_arch_%d_%s.pdf';
            }
            else
            {
                $filename        = 'docto_%d_scan_%d_arch_%d_foraneo_%s.pdf';
            }

            if( $nombre_request = $request->get('nombre_escaneo',false) )
            {
                $nombre_escaneo = $nombre_request; // Asignamos el nombre que nos ha especificado el usuario para el escaneo
            }
            else
            {
                $nombre_escaneo = sprintf('Documento_%s_%s',$documento->getFolio(),time()); // Asignamos un nombre al escaneo por default
            }

            DB::beginTransaction();

            $archivo = new MArchivo;
            $archivo->ARCH_FOLDER   = 'app/escaneos';
            $archivo->ARCH_FILENAME = '';
            $archivo->ARCH_PATH     = '';
            $archivo->ARCH_TYPE     = $escaneo_file->extension();
            $archivo->ARCH_MIME     = $escaneo_file->getMimeType();
            $archivo->ARCH_SIZE     = $escaneo_file->getClientSize();
            $archivo->save();

            $escaneo = new MEscaneo;
            $escaneo->ESCA_ARCHIVO         = $archivo->getKey(); 
            $escaneo->ESCA_DOCUMENTO_LOCAL = $documento->getKey(); 
            $escaneo->ESCA_NOMBRE          = $nombre_escaneo; 
            $escaneo->save();

            $filename = sprintf($filename,$documento->getKey(), $escaneo->getKey(), $archivo->getKey(), time());
            
            $escaneo_file->move(storage_path($archivo->getFolder()), $filename);
            
            $archivo->ARCH_FILENAME = $filename;
            $archivo->ARCH_PATH     = $archivo->getFolder() . '/' . $filename;
            $archivo->save();

            DB::commit();

            return $this->responseSuccessJSON(); 
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON( $error->getMessage() );
        }

    }

}