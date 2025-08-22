<?php

namespace App\Domains\Role\Policy;

use App\Domains\Role\Model\Role;
use App\Domains\User\Model\User;

class RolePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function indexAndWithPagination(User $user)
    {
        return $user->hasAnyPermission(['admin.role.create', 'admin.role.update', 'admin.role.delete', 'admin.role.read']);
    }

    public function show(User $user)
    {
        return $user->hasAnyPermission(['admin.role.create', 'admin.role.update', 'admin.role.delete', 'admin.role.read', 'admin.profile.read']);
    }
    public function store(User $user)
    {
        return $user->hasAnyPermission('admin.role.create');
    }
    public function update(User $user, Role $role)
    {
        return $user->hasAnyPermission('admin.role.update') && ($role->name != 'Administrador' && $role->name != 'Cliente');
    }
    public function destroy(User $user, Role $role)
    {
        return $user->hasAnyPermission('admin.role.delete') && ($role->name != 'Administrador' && $role->name != 'Cliente');
    }
    public function componentSelect(User $user)
    {
        return $user->hasAnyPermission([
            'admin.employee.create',
            'admin.employee.update',
            'admin.employee.read',
            'admin.role.create',
            'admin.role.update',
            'admin.role.read'
        ]);
    }
}
