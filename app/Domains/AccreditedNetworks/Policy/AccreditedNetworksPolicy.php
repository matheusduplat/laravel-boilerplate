<?php

namespace App\Domains\AccreditedNetworks\Policy;

use App\Domains\Customer\Model\Customer;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\User\Model\User;

class AccreditedNetworksPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function indexAndWithPagination(User|Customer $user): bool
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.accreditedNetworks.create', 'admin.accreditedNetworks.update', 'admin.accreditedNetworks.delete', 'admin.accreditedNetworks.read']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User $user): bool
    {
        return $user->hasAnyPermission(['admin.accreditedNetworks.create']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User|Customer $user): bool
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.accreditedNetworks.update']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user): bool
    {
        return $user->hasAnyPermission(['admin.accreditedNetworks.delete']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->hasAnyPermission(['admin.accreditedNetworks.restore']);
    }
    public function show(User|Customer $user): bool
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.accreditedNetworks.read']);
    }
}
