<?php
namespace App\Http\Controllers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class BaseController extends Controller
{
	public function __construct( $data = [] )
    {
    }

    public function setLog( $file = 'log' )
    {
        $logFile = storage_path( 'logs/' . $file);
        $this->monolog = new Logger('log');
        $this->monolog->pushHandler(new StreamHandler($logFile), Logger::INFO);
        
        return $this;
    }

    /**
     * Crea un log en el archivo especificado y escribe el log
     * @param string $level
     * @param string $message
     * @param null $data
     */
    public function Log($level = 'info', $message='', $data=[]){

        $loglevel = Logger::DEBUG;

        switch ($level){
            case 'info' :
                $loglevel = Logger::INFO;
                break;
            case 'error' :
                $loglevel = Logger::ERROR;
                break;
            case 'alert' :
                $loglevel = Logger::ALERT;
                break;
            case 'notice' :
                $loglevel = Logger::NOTICE;
                break;
            default :
                $loglevel = Logger::DEBUG;
                break;
        }

        $this->monolog->log( $loglevel, $message, $data );
    }

    protected function responseSuccessJSON( $message = '', $type = null, $tables = null )
    {
        return $this->buildValues(true, $message, 'success', $type, $tables);
    }

    protected function responseInfoJSON( $message = '', $type = null, $tables = null )
    {
        return $this->buildValues(true, $message, 'info', $type, $tables);
    }

    protected function responseWarningJSON( $message = '', $type = null, $tables = null )
    {
        return $this->buildValues(true, $message, 'warning', $type, $tables);
    }

    protected function responseDangerJSON( $message = '', $type = null, $tables = null )
    {
        return $this->buildValues(true, $message, 'danger', $type, $tables);
    }

    protected function responseErrorJSON( $message = '', $type = null, $tables = null )
    {
        return $this->buildValues(false, $message, 'danger', $type, $tables);
    }

    protected function responseTypeJSON( $message = '', $type = 'success' )
    {
        $values = $this->mergeValues(['status'=>true,'type'=>$type], $message);

        return response()->json( $values );
    }

    protected function responseJSON( $status, $message, $type, $tables = null )
    {
        $values = $this->mergeValues(['status'=>$status,'type'=>$type,'tables'=>$tables], $message);

        return response()->json( $values );
    }

    private function buildValues( $status, $message, $type, $type2, $tables )
    {
        if( is_null($tables) )
        {
            $tables = $type2;
        }else if( !is_null($type2) )
        {
            $type = $type2;
        }

        return $this->responseJSON( $status, $message, $type, $tables );
    }

    private function mergeValues( $values, $message )
    {
        if( is_array($message) )
        {
            $values = array_merge($values, $message);
        }
        else
        {
            $values['message'] = $message;
        }

        if (array_key_exists('tables',$values) && is_null($values['tables']) )
        {
            unset($values['tables']);
        }

        return $values;
    }

}