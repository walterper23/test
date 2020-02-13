<?php
namespace App\Services;

use Illuminate\Auth\Passwords\PasswordBroker;  
use Doctrine\Instantiator\Exception\UnexpectedValueException;
use Illuminate\Support\Arr;  

class CustomPasswordBroker extends PasswordBroker    
{
	public function getUser(array $credentials)
    {
        $credentials = Arr::except($credentials, ['token']);

        $user = $this->users->retrieveByCredentials($credentials);

        /*if ($user && ! $user instanceof CanResetPasswordContract) {
            throw new UnexpectedValueException('User must implement CanResetPassword interface.');
        }*/

        if ($user && $user -> isSuperAdmin())
        	$user = null;

        return $user;
    }

}