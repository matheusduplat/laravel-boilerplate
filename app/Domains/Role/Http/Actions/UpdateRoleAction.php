<?php

namespace App\Domains\Role\Http\Actions;

use App\Domains\Role\Model\Role;
use Illuminate\Support\Facades\Auth;

class UpdateRoleAction
{
    public function execute(array $data, Role $role)
    {
        $data['updated_by'] = Auth::user()->name ?? null;
        $role->update($data);
        $role->syncPermissionsWithAudit($data['permissions']);
        return $role;
    }
}
