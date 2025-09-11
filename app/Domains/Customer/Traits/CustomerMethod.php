<?php

namespace App\Domains\Customer\Traits;

use App\Domains\Audit\Service\AuditService;
use App\Notifications\CreatePasswordNotification;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyMailNotification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

trait CustomerMethod
{
    public function sendPasswordResetNotification($token): void
    {
        $url = config('app.url_front') . '/cliente/redefinir-senha';
        $this->notify(new ResetPasswordNotification($token, $url));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyMailNotification(URL::signedRoute('auth.customer.verification.verify', ['customer_id' => $this->id])));
    }

    public function sendCreatePasswordNotification(): void
    {
        Password::broker('create_password_customer')->sendResetLink(
            ['email' => $this->email],
            function ($user, $token) {
                $url = config('app.url_front') . '/cliente/criar-senha';
                $user->notify(new CreatePasswordNotification($token, $url));
            }
        );
    }
    public function relationLoadWithTrashed(): void
    {
        $this->loadMissing([
            'holder' => function ($query) {
                $query->withTrashed();
            },
            'phones' => function ($query) {
                $query->withTrashed();
            },
            'address' => function ($query) {
                $query->withTrashed()->with('stateable');
            },
        ]);
    }
    public function attachRolesWithAudit(array $roles): void
    {
        if (empty($roles)) return;
        $this->roles()->attach($roles);

        AuditService::auditManyToMany($this, 'roles_customer_attach', [], $roles, 'roles_id');
    }
    public function attachPermissionsWithAudit(array $permissions): void
    {
        if (empty($permissions)) return;

        $this->permissions()->attach($permissions);

        AuditService::auditManyToMany($this, 'permissions_customer_attach', [], $permissions, 'permissions_id');
    }

    public function syncRolesWithAudit(array $roles): void
    {

        $oldRoles = $this->roles()->get()->pluck('id')->toArray();

        $this->roles()->sync($roles);

        if ($oldRoles !== $roles) {
            AuditService::auditManyToMany($this, 'roles_customer_sync', $oldRoles, $roles, 'roles_id');
        }
    }
    public function syncPermissionsWithAudit(array $permissions): void
    {
        $oldPermissions = $this->permissions()->get()->pluck('id')->toArray();

        $this->permissions()->sync($permissions);

        if ($oldPermissions !== $permissions) {
            AuditService::auditManyToMany($this, 'permissions_customer_sync', $oldPermissions, $permissions, 'permissions_id');
        }
    }
}
