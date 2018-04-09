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
     * Enable or disable the autocommit transaccion
     * @param bool|true $commit
     */
    public function setAutocommit($commit=true)
    {
        $this->autoCommit = $commit;
    }

    /**
     * Returns an associative array of object properties
     *
     * @access	public
     * @param	string  $error , name of de error cause the exception ex. error = array(code, message)
     * @param	integer $code  , code of de error
     * @param	string  $msg   , description of the message
     * @param   string  $trace , descripcion de error que paso
     * @return	array[code] = message
     */
    public function setError($error, $code, $msg, $trace=''){
        $this->errors[$error] = array('code'=>$code, 'message'=>$msg, 'trace'=>$trace);
    }

    /**
     * Returns an associative array of object properties
     *
     * @access	public
     * @param	string  $error, if empty return all array with errors, else return property
     * @return	array[code] = message
     */
    public function getError( $error = null ){
        if ($error){
            if ( !array_key_exists($error, $this->errors) ){
                return false;
            }else{
                return $this->errors[$error];
            }
        }
        return $this->errors;
    }

    /**
     * clear error array
     */
    public function resetError(){
    	$this->errors = array();
    }

    /**
     * @param Exception $e
     * @return array
     */
    public function TraceError( $e){
        $error = $this->getError('error');
        $errores = array();

        if($error){
           $errors = array(
               'message'    => $error['message'],
               'code'       => $error['code'],
               'trace'      => isset($error['trace']) ? $error['trace'] : "MENSAJE SIN DESCRIPCIÓN DEL ERROR"
           );
        }else{
            $errors = array(
                'message'    => $e->getMessage(),
                'code'       => $e->getCode(),
                'line'       => $e->getLine(),
                'file'       => $e->getFile()
            );
        }
        return $errors;
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
        return $this -> buildValues(true, $message, 'success', $type, $tables);
    }

    protected function responseInfoJSON( $message = '', $type = null, $tables = null )
    {
        return $this -> buildValues(true, $message, 'info', $type, $tables);
    }

    protected function responseWarningJSON( $message = '', $type = null, $tables = null )
    {
        return $this -> buildValues(true, $message, 'warning', $type, $tables);
    }

    protected function responseErrorJSON( $message = '', $type = null, $tables = null )
    {
        return $this -> buildValues(false, $message, 'danger', $type, $tables);
    }

    protected function responseTypeJSON( $message = '', $type )
    {
        $values = $this -> mergeValues(['status'=>true,'type'=>$type], $message);
        return response() -> json( $values );
    }

    protected function responseJSON( $status, $message, $type, $tables = null )
    {
        $values = $this -> mergeValues(['status'=>$status,'type'=>$type,'tables'=>$tables], $message);
        return response() -> json( $values );
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

        return $this -> responseJSON( $status, $message, $type, $tables );
    }

    private function mergeValues( $values, $message )
    {
        if( is_array($message) )
        {
            array_merge($values, $message);
        }else{
            $values['message'] = $message;
        }

        if( isset($values['tables']) && is_null($values['tables']) )
            unset($values['tables']);

        return $values;
    }

}