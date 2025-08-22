<?php

namespace App\Domains\Role\Http\Actions;

use App\Domains\Role\Model\Role;
use Illuminate\Support\Facades\Auth;

class DeleteRoleAction
{
    public function execute(Role $role)
    {
        $data['deleted_by'] = Auth::user()->name ?? null;
        $role->update($data);
        $role->delete();
        return $role;
    }
}
