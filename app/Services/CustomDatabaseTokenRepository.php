<?php
namespace App\Services;

use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class CustomDatabaseTokenRepository extends DatabaseTokenRepository
{
    
    public function create(CanResetPasswordContract $user)
    {
        $email = $user->getEmailForPasswordReset();

        $this->deleteExisting($user);

        // We will create a new, random token for the user so that we can e-mail them
        // a safe link to the password reset form. Then we will insert a record in
        // the database so that we can verify the token within the actual reset.
        $token = $this->createNewToken();

        $insert = [
            'PASS_USUARIO'    => $user -> getKey(),
            'PASS_USERNAME'   => $user -> getAuthUsername(),
            'PASS_EMAIL'      => $email,
            'PASS_TOKEN'      => $this->hasher->make($token),
            'PASS_CREATED_AT' => new Carbon
        ];

        $this -> getTable() -> insert($insert);

        return $token;
    }

    protected function deleteExisting(CanResetPasswordContract $user)
    {
        return $this -> getTable() -> where('PASS_USERNAME',$user -> getAuthUsername()) -> where('PASS_EMAIL', $user->getEmailForPasswordReset()) -> delete();
    }

    public function exists(CanResetPasswordContract $user, $token)
    {
        $record = (array) $this->getTable()->where(
            'PASS_USERNAME', $user->getEmailForPasswordReset()
        )->first();

        return $record &&
               ! $this->tokenExpired($record['PASS_CREATED_AT']) &&
                 $this->hasher->check($token, $record['PASS_TOKEN']);
    }

    public function deleteExpired()
    {
        $expiredAt = Carbon::now()->subSeconds($this->expires);

        $this->getTable()->where('PASS_CREATED_AT', '<', $expiredAt)->delete();
    }

}