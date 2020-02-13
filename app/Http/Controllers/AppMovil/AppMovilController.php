<?php
namespace App\Http\Controllers\AppMovil;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AppMovilController extends Controller
{
    public function index()
    {
    	$users = DB::table('usuarios')->get();
    	return ['results' => $users];
    }

    public function correo()
    {
    	$users = DB::table('detalles')
            ->join('documentos', 'detalles.DETA_DETALLE', '=', 'documentos.DOCU_DETALLE')
            ->join('municipios', 'detalles.DETA_MUNICIPIO', '=', 'municipios.MUNI_MUNICIPIO')
            ->join('seguimiento','documentos.DOCU_DOCUMENTO','=','seguimiento.SEGU_DOCUMENTO')
            ->join('system_tipos_documentos','documentos.DOCU_SYSTEM_TIPO_DOCTO','=','system_tipos_documentos.SYTD_TIPO_DOCUMENTO')
            ->join('system_estados_documentos','documentos.DOCU_SYSTEM_ESTADO_DOCTO','=','system_estados_documentos.SYED_ESTADO_DOCUMENTO')
            ->orderBy('detalles.DETA_FECHA_RECEPCION', 'DESC')
            ->select('*')
            ->get();
        $respuesta=['results' => $users];
        return response()->json($respuesta);
// }
            // return response()->json('resilt' => $users);
    }
    public function Grafica($tipo,$fecha1,$fecha2)
    {
    	$results = $tipo.$fecha1.$fecha1;

    	$grafica =DB::table('detalles')
    		->join('municipios','detalles.DETA_MUNICIPIO','=','municipios.MUNI_MUNICIPIO')
    		->join('documentos','detalles.DETA_DETALLE','=','documentos.DOCU_DETALLE')
    		->where('documentos.DOCU_SYSTEM_ESTADO_DOCTO', '=', $tipo)
    		->where('documentos.DOCU_CREATED_AT', '>=', $fecha1)
    		->where('documentos.DOCU_CREATED_AT', '<=', $fecha2)
    		->groupBy('detalles.DETA_MUNICIPIO')
    		->select('municipios.MUNI_MUNICIPIO',DB::raw('count(*) as total'))
    		->get();
    	$respuesta= ['results' => $grafica];
    	return response()->json($respuesta);
    }
    public function login($usuario,$pass)
    {
    	$contraseña_hash="";
    	$respuesta ="";
    	$login_psw = DB::table('usuarios')
    		->where('USUA_USERNAME', '=',$usuario)
    		->select('USUA_PASSWORD')
    		->get();
    	if ($login_psw->isEmpty()) {
    			//usuario no existe
			$respuesta = array('results' => FALSE);
    	}else{
    		foreach($login_psw as $t){
    			$contraseña_hash = $t -> USUA_PASSWORD;
			} 
    		if (password_verify($pass, $contraseña_hash)) {
	    			$respuesta = array('results' => TRUE);
				} else {
				    $respuesta = array('results' => FALSE);
				}
		}
    		
		return response()->json($respuesta);
    }
    public function updateLeidos($id_doc,$id_user)
    {
        $update = DB::table('seguimiento')
            ->where('SEGU_SEGUIMIENTO', $id_doc)
            ->update(['SEGU_LEIDO' => $id_user]);
            $respuesta= ['results' => $update];
        return response()->json($respuesta);
    }
    public function idUser($usuario)
    {
        $id;
        $userId = DB::table('usuarios')
            ->where('USUA_USERNAME', '=',$usuario)
            ->select('USUA_USUARIO')
            ->get();
        foreach($userId as $t){
                $id = $t -> USUA_USUARIO;
            }     
            $respuesta= ['results' => $id];
        return response()->json($respuesta);
    }
    public function token()
    {
    	$proyectos = array('results' => "1234");
		return response()->json($proyectos);
    }
    public function IdAPP($id)
    {
    	$verificacion = DB::table('ident_app')
    		->where('token_app', '=',$id)
    		->select('token_app')
    		->get();
    	if ($verificacion->isEmpty()) {
	    	$Resgistro = DB::table('ident_app')->insert(
			    ['token_app' => $id]
			);
    	}
    	$respuesta = array('results' => $id);
    	return response()->json($respuesta);
    	// return $id;
    }

    public function notificacion()
    {
    	$tokensID = DB::table('ident_app')
    		->select('token_app')
    		->get();
    		foreach ($tokensID as $o) {
				// return $o->token_app;
		    	define( 'AIzaSyBjK11bX0m9fCcaI6JulmxitLDG3TNzPWI', 'AAAAHt9Z4aM:APA91bHhlHxtiMzAk5fPXG-fK_TlLLlHSZipeXo55WDlkI-hfxvOKFyTZpNU63EMlNP5hv9xPU_Js5K9-IcappbjQn64n97aY5bTF0EuIM8tvGxBwClX-8t9bIrnBDUSTccDYgA-eoE4' );
				$registrationIds = [$o->token_app];
				// prep the bundle
				$msg = array
				(
				   'body'   => 'Tiene nuevas denuncias sin ver',
				    'title' => 'PPA',
				    'icon'  => 'myicon',/*Default Icon*/
				    'sound' => 'mySound'/*Default sound*/
				);
				$fields = array
				(
				    'registration_ids'  => $registrationIds,
				    'data'          => $msg
				);
				 
				$headers = array
				(
				    'Authorization: key=AIzaSyBjK11bX0m9fCcaI6JulmxitLDG3TNzPWI',
				    'Content-Type: application/json'
				);
				 
				$ch = curl_init();
				curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
				curl_setopt( $ch,CURLOPT_POST, true );
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
				$result = curl_exec($ch );
				curl_close( $ch );
				echo $result;
			}
    }
}
