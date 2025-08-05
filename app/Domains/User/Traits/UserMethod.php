<?php

namespace App\Domains\User\Traits;

use Illuminate\Support\Facades\Hash;

trait UserMethod
{
    public function sendPasswordResetNotification($token): void
    {

        // $url =  config('mail.url-reset-senha') .  $token;
        // $this->notify(new SendResetPassword($url));
    }

    public function sendEmailVerificationNotification()
    {
        // $this->notify(new VerifyMailNotification(URL::signedRoute('verification.verify', ['id' => $this->id])));
    }
}
