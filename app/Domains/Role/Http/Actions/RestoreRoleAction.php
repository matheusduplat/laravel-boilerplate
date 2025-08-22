<?php

namespace App\Domains\Role\Http\Actions;

use App\Domains\Role\Model\Role;
use Illuminate\Support\Facades\Auth;

class RestoreRoleAction
{
    public function execute(Role $role)
    {
        $data['deleted_by'] = null;
        $role->update($data);
        $role->restore();
        return $role;
    }
}
