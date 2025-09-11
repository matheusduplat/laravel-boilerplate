<?php

namespace App\Domains\Customer\Policy;

use App\Domains\Customer\Model\Customer;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\User\Model\User;
use Illuminate\Auth\Access\Response;

class CustomerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function indexAndWithPagination(User $user): bool
    {
        return $user->hasAnyPermission(['admin.customer.create', 'admin.customer.update', 'admin.customer.delete', 'admin.customer.read']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User $user): bool
    {
        return $user->hasAnyPermission(['admin.customer.create']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User|Customer $user, Customer $customer): bool
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.customer.update']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Customer $customer): bool
    {
        return $user->hasAnyPermission(['admin.customer.delete']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Customer $customer): bool
    {
        return $user->hasAnyPermission(['admin.customer.restore']);
    }
    public function show(User|Customer $user, Customer $customer): bool
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.customer.read']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Customer $customer): bool
    {
        return false;
    }
    public function componentSelect(User $user)
    {
        return $user->hasAnyPermission([
            'admin.customer.create',
            'admin.customer.update',
            'admin.customer.delete',
            'admin.customer.read'
        ]);
    }
    public function updateAddress(User|Customer $user, Customer $customer)
    {
        if ($user instanceof Customer && $user->id !== $customer->id) {
            abort(403, __("You cannot edit another customer's address."));
        }
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.customer.update']);
    }
    public function updatePhone(User|Customer $user, Customer $customer)
    {
        if ($user instanceof Customer && $user->id !== $customer->id) {
            abort(403, __("You cannot edit another customer's phones."));
        }
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.customer.update']);
    }
    public function updatePerfil(User|Customer $user, Customer $customer)
    {
        if ($user instanceof Customer && $user->id !== $customer->id) {
            abort(403, __("You cannot edit another customer's profile."));
        }
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.customer.update']);
    }
    public function updatePassword(User|Customer $user, Customer $customer)
    {
        if ($user instanceof Customer && $user->id !== $customer->id) {
            abort(403, __("You cannot edit another customer's password."));
        }
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.customer.update']);
    }
}
