<?php

namespace App\Domains\Employee\Policy;

use App\Domains\User\Model\User;

class EmployeePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function indexAndWithPagination(User $user)
    {
        return $user->hasAnyPermission(['admin.employee.create', 'admin.employee.update', 'admin.employee.delete', 'admin.employee.read']);
    }

    public function show(User $user)
    {
        return $user->hasAnyPermission(['admin.employee.create', 'admin.employee.update', 'admin.employee.delete', 'admin.employee.read']);
    }
    public function store(User $user)
    {
        return $user->hasAnyPermission(['admin.employee.create']);
    }
    public function update(User $user)
    {
        return $user->hasAnyPermission(['admin.employee.update']);
    }
    public function destroy(User $user)
    {
        return $user->hasAnyPermission(['admin.employee.delete']);
    }
    public function restore(User $user)
    {
        return $user->hasAnyPermission(['admin.employee.delete']);
    }
    public function perfil(User $user)
    {
        return $user->hasAnyPermission(['admin.profile.read']);
    }
    public function componentSelect(User $user)
    {
        return $user->hasAnyPermission(['admin.employee.create', 'admin.employee.update', 'admin.employee.read']);
    }
}
