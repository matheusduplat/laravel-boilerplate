<?php

namespace App\Domains\Contact\Policy;

use App\Domains\Contact\Model\Contact;
use App\Domains\Customer\Model\Customer;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\User\Model\User;

class ContactPolicy
{
    public function indexAndWithPagination(User $user)
    {
        return $user->hasAnyPermission(['admin.contact.create', 'admin.contact.update', 'admin.contact.delete', 'admin.contact.read']);
    }

    public function show(User|Customer $user)
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.contact.create', 'admin.contact.update', 'admin.contact.delete', 'admin.contact.read']);
    }
    public function store(User|Customer $user)
    {
        return $user->hasAnyPermission(['admin.contact.create']) || $user->hasAnyRole([RoleDefaults::CLIENT]);
    }
    public function update(User|Customer $user, Contact $contact)
    {
        if ($user instanceof Customer && $user->id !== $contact->customer_id) {
            return abort(403, __("You cannot edit another customer's contact."));
        }

        return $user->hasAnyPermission(['admin.contact.update']) || $user->hasAnyRole([RoleDefaults::CLIENT]);
    }
    public function destroy(User|Customer $user, Contact $contact)
    {
        if ($user instanceof Customer && $user->id !== $contact->customer_id) {
            return abort(403, __("You cannot delete another customer's contact."));
        }
        return $user->hasAnyPermission(['admin.contact.delete']) || $user->hasAnyRole([RoleDefaults::CLIENT]);
    }
    public function restore(User $user)
    {
        return $user->hasAnyPermission(['admin.contact.restore']);
    }
    public function toCustomer(Customer $user)
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]);
    }
}
