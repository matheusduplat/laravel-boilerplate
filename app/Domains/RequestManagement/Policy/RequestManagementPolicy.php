<?php

namespace App\Domains\RequestManagement\Policy;

use App\Domains\Customer\Model\Customer;
use App\Domains\RequestManagement\Model\RequestManagement;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\User\Model\User;

class RequestManagementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function indexAndWithPagination(User|Customer $user): bool
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.RequestManagement.create', 'admin.RequestManagement.update', 'admin.RequestManagement.delete', 'admin.RequestManagement.read']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User|Customer $user): bool
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.RequestManagement.create']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User|Customer $user, RequestManagement $requestManagement): bool
    {
        if ($user instanceof Customer && $user->id !== $requestManagement->customer_id) {
            abort(403, __("You cannot edit another customer's requests."));
        }
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.RequestManagement.update']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User|Customer $user, RequestManagement $requestManagement): bool
    {
        if ($user instanceof Customer && $user->id !== $requestManagement->customer_id) {
            abort(403, __("You cannot delete another customer's requests."));
        }
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.RequestManagement.delete']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->hasAnyPermission(['admin.RequestManagement.restore']);
    }
    public function show(User|Customer $user, RequestManagement $requestManagement): bool
    {
        if ($user instanceof Customer && $user->id !== $requestManagement->customer_id) {
            abort(403, __("You cannot view another customer's requests."));
        }
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.RequestManagement.read']);
    }
    public function toCustomer(User|Customer $user): bool
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.RequestManagement.read']);
    }
}
