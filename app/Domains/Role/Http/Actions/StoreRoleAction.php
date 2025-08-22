<?php

namespace App\Domains\Role\Http\Actions;

use App\Domains\Role\Model\Role;
use Illuminate\Support\Facades\Auth;

class StoreRoleAction
{
    public function execute(array $data)
    {
        $data['created_by'] = Auth::user()->name ?? null;
        $role = Role::create($data);
        $role->attachPermissionsWithAudit($data['permissions']);
        return $role;
    }
}
