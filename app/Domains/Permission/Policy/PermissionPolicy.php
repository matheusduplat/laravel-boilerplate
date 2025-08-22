<?php

namespace App\Domains\Permission\Policy;

use App\Domains\User\Model\User;

class PermissionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function componentSelect(User $user)
    {
        return $user->hasAnyPermission(
            [
                'admin.employee.create',
                'admin.employee.update',
                'admin.employee.read',
                'admin.role.create',
                'admin.role.update',
                'admin.role.read',
                'admin.profile.read'
            ]
        );
    }
}
