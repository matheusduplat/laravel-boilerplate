<?php

namespace App\Domains\RequestManagementResponse\Policy;

use App\Domains\Customer\Model\Customer;
use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\User\Model\User;

class RequestManagementResponsepolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function indexAndWithPagination(User|Customer $user): bool
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.RequestManagementResponse.create', 'admin.RequestManagementResponse.update', 'admin.RequestManagementResponse.delete', 'admin.RequestManagementResponse.read']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User|Customer $user): bool
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.RequestManagementResponse.create']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User|Customer $user, RequestManagementResponse $requestManagementResponse): bool
    {
        if ($user instanceof Customer && $user->id !== $requestManagementResponse->requestManagement->customer_id) {
            abort(403, __("You cannot update another client's response"));
        }
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.RequestManagementResponse.update']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User|Customer $user, RequestManagementResponse $requestManagementResponse): bool
    {
        if ($user instanceof Customer && $user->id !== $requestManagementResponse->requestManagement->customer_id) {
            abort(403, __("You cannot delete another client's response"));
        }
        return $user->hasAnyPermission(['admin.RequestManagementResponse.delete']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->hasAnyPermission(['admin.RequestManagementResponse.restore']);
    }
    public function show(User|Customer $user, RequestManagementResponse $requestManagementResponse): bool
    {
        if ($user instanceof Customer && $user->id !== $requestManagementResponse->requestManagement->customer_id) {
            abort(403, __("You cannot view another client's response"));
        }
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.RequestManagementResponse.read']);
    }
}
