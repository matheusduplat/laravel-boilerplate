<?php

namespace App\Domains\User\Http\Actions;

use App\Domains\User\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeleteUserAction
{
    public function execute(User $user)
    {
        $user->update([
            'deleted_by' => Auth::user()->name ?? null
        ]);
        $user->delete();
        return $user;
    }
}
