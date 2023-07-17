<?php

namespace App\Domains\User\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }
    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
    {
        $user->created_by = auth()->user()->id ?? '1';
    }
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }
    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        $user->updated_by = auth()->user()->id ?? '1';
    }
    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }
    /**
     * Handle the User "updating" event.
     */
    public function deleting(User $user): void
    {
        $user->update(['deleted_by' => auth()->user()->id ?? '1']);
    }
    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
