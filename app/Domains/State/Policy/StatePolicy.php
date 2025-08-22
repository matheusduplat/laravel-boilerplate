<?php

namespace App\Domains\State\Policy;

use App\Domains\User\Model\User;

class StatePolicy
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
        return $user->hasAnyPermission([
            'admin.customer.create',
            'admin.customer.update',
            'admin.customer.read'
        ]);
    }
}
