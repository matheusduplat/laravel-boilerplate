<?php

namespace App\Models\Traits\Method;

use App\Notifications\SendResetPassword;

/**
 * Trait UserMethod.
 */
trait UserMethod
{
    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function sendPasswordResetNotification($token): void
    {

        $url =  config('mail.url-reset-senha') .  $token;
        $this->notify(new SendResetPassword($url));
    }
}
