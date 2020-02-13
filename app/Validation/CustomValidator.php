<?php

namespace App\Validation;
 
class CustomValidator extends \Illuminate\Validation\Validator {

	public function __construct( $translator, $data, $rules, $messages = [], $customAttributes = [] ) {
        parent::__construct( $translator, $data, $rules, $messages, $customAttributes );

        
    }

    public function validateDeleted($attribute, $value, $parameters){

    	$row = \DB::table($parameters[0])->where($parameters[1],$value)->where($parameters[2],0)->first();

    	if($row) return true;

    	$this->setCustomMessages(['deleted' => 'El recurso no existe']);
		return false;
	}

}