<?php

namespace App\Domains\User\Http\Actions;

use App\Domains\User\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UpdatePasswordUserAction
{
    public function execute(User $user, array $data)
    {
        $data['updated_by'] = Auth::user()->name ?? null;
        $user->update($data);
        return $user;
    }
}
