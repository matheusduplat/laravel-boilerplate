<?php

namespace App\Domains\User\Traits;

use App\Domains\Audit\Service\AuditService;
use Illuminate\Support\Facades\Hash;

trait UserMethod
{
    // public function sendPasswordResetNotification($token): void
    // {

    //     // $url =  config('mail.url-reset-senha') .  $token;
    //     // $this->notify(new SendResetPassword($url));
    // }

    // public function sendEmailVerificationNotification()
    // {
    //     // $this->notify(new VerifyMailNotification(URL::signedRoute('verification.verify', ['id' => $this->id])));
    // }
    public function assignRoles($roles): void
    {
        $oldRoleIds = $this->roles()->pluck('roles.id')->toArray();

        $this->roles()->attach($roles);

        AuditService::auditManyToMany($this, 'roles_user_sync', $oldRoleIds, $roles, 'roles_id');
    }
    public function isAdmin(): bool
    {
        return $this->hasRole('Administrador');
    }
}
