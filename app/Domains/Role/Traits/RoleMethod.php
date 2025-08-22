<?php

namespace App\Domains\Role\Traits;

use App\Domains\Audit\Service\AuditService;

trait RoleMethod
{
    public function attachPermissionsWithAudit(array $permissions): void
    {
        if (empty($permissions)) return;

        $this->permissions()->attach($permissions);

        AuditService::auditManyToMany($this, 'permissions_role_attach', [], $permissions, 'permissions_id');
    }

    public function syncPermissionsWithAudit(array $permissions): void
    {
        $oldPermissions = $this->permissions()->get()->pluck('id')->toArray();

        $this->permissions()->sync($permissions);

        if ($oldPermissions !== $permissions) {
            AuditService::auditManyToMany($this, 'permissions_role_sync', $oldPermissions, $permissions, 'permissions_id');
        }
    }
}
