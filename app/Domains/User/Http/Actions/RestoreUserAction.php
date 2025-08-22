<?php

namespace App\Domains\User\Http\Actions;

use App\Domains\User\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RestoreUserAction
{
    public function execute(User $user)
    {
        $data['deleted_by'] = null;
        $user->update($data);
        $user->restore();
        return $user;
    }
}
