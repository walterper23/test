<?php
namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests\AnexoRequest;
use Illuminate\Support\Facades\Input;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\AnexosDataTable;

/* Models */
use App\Model\Catalogo\MAnexo;

class AnexoController extends BaseController
{
    private $form_id = 'form-anexo';

    public function __construct()
    {
        parent::__construct();
        $this -> setLog('AnexoController.log');
    }

    public function index(AnexosDataTable $dataTables)
    {
        $data['table']    = $dataTables;
        $data['form_id']  = $this -> form_id;
        $data['form_url'] = url('configuracion/catalogos/anexos/nuevo');

        return view('Configuracion.Catalogo.Anexo.indexAnexo')->with($data);
    }

    public function manager(AnexoRequest $request)
    {
        switch ($request -> action) {
            case 1: // Nuevo
                $response = $this -> nuevoAnexo( $request );
                break;
            case 2: // Editar
                $response = $this -> editarAnexo( $request );
                break;
            case 3: // Visualizar anexo
                $response = $this -> verAnexo( $request );
                break;
            case 4: // Activar / Desactivar
                $response = $this -> activarAnexo( $request );
                break;
            case 5: // Eliminar
                $response = $this -> eliminarAnexo( $request );
                break;
            default:
                return response() -> json(['message'=>'Petición no válida'],404);
                break;
        }

        return $response;
    }

    public function postDataTable(AnexosDataTable $dataTables)
    {
        return $dataTables->getData();
    }

    public function formNuevoAnexo(Request $request){
        try {

            $data = [];

            $data['title']         = 'Nuevo anexo';
            $data['url_send_form'] = url('configuracion/catalogos/anexos/manager');
            $data['form_id']       = $this -> form_id;
            $data['modelo']        = null;
            $data['action']        = 1;
            $data['recepcion']     = $request -> get('recepcion',false);
            $data['id']            = null;

            return view('Configuracion.Catalogo.Anexo.formAnexo')->with($data);

        } catch(Exception $error) {

        }
    }

    public function nuevoAnexo( $request )
    {
        try {
            $anexo = new MAnexo;
            $anexo -> ANEX_NOMBRE = $request -> nombre;
            $anexo -> save();

            $message = sprintf('<i class="fa fa-fw fa-clipboard"></i> Anexo <b>%s</b> creado',$anexo -> getCodigo());
            
            if ($request -> recepcion)
            {
                return $this -> responseSuccessJSON([
                    'message' => $message,
                    'anexo'   => [
                        'id'     => $anexo -> getKey(),
                        'nombre' => $anexo -> getNombre()
                    ]
                ]);
            }
            else
            {
                $tables = ['dataTableBuilder',null,true];
                return $this -> responseSuccessJSON($message,$tables);
            }

        } catch(Exception $error) {

        }
    }

    public function formEditarAnexo(){
        try {
            $data['title']         = 'Editar anexo';
            $data['url_send_form'] = url('configuracion/catalogos/anexos/manager');
            $data['form_id']       = $this -> form_id;
            $data['modelo']        = MAnexo::find( Input::get('id') );
            $data['action']        = 2;
            $data['id']            = Input::get('id');

            return view('Configuracion.Catalogo.Anexo.formAnexo')->with($data);

        } catch(Exception $error) {

        }
    }

    public function editarAnexo( $request )
    {
        try {
            $anexo = MAnexo::find( $request -> id );
            $anexo -> ANEX_NOMBRE = $request -> nombre;
            $anexo -> save();

            $message = sprintf('<i class="fa fa-fw fa-check"></i> Anexo <b>%s</b> modificado',$anexo -> getCodigo());

            $tables = 'dataTableBuilder';

            return $this -> responseSuccessJSON($message,$tables);

        } catch(Exception $error) {
            
        }
    }

    public function verAnexo( $request )
    {
        try {
            $anexo         = MAnexo::find( $request -> id );
            $data['anexo'] = $anexo;
            $data['title'] = sprintf('Anexo #%s', $anexo -> getCodigo() );
            return view('Configuracion.Catalogo.Anexo.verAnexo') -> with($data);
        } catch(Exception $error) {

        }

    }

    public function activarAnexo( $request )
    {
        try {
            $anexo = MAnexo::find( $request -> id );
            $anexo -> cambiarDisponibilidad() -> save();
            
            if ( $anexo -> disponible() )
            {
                $message = sprintf('<i class="fa fa-fw fa-check"></i> Anexo <b>%s</b> activado',$anexo -> getCodigo());
                return $this -> responseInfoJSON($message);
            }
            else
            {
                $message = sprintf('<i class="fa fa-fw fa-warning"></i> Anexo <b>%s</b> desactivado',$anexo -> getCodigo());
                return $this -> responseWarningJSON($message);
            }

        } catch(Exception $error) {

        }
    }

    public function eliminarAnexo( $request )
    {
        try {
            $anexo = MAnexo::find( $request -> id );
            $anexo -> eliminar() -> save();

            // Lista de tablas que se van a recargar automáticamente
            $tables = 'dataTableBuilder';

            $message = sprintf('<i class="fa fa-fw fa-warning"></i> Anexo <b>%s</b> eliminado',$anexo -> getCodigo());

            return $this -> responseWarningJSON($message,'danger',$tables);
        } catch(Exception $error) {
            return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el anexo. Error ' . $error->getMessage() ]);
        }
    }

}