<?php

namespace App\Domains\User\Http\Resources;

use App\Domains\Employee\Http\Resources\EmployeeResource;
use App\Domains\Permission\Model\Permission;
use App\Domains\Role\Http\Resource\RoleResource;
use App\Domains\Role\Model\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{


    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' =>  RoleResource::collection($this->roles),
            'role' => new RoleResource($this->roles->first()),
            'permissions' =>  $this->getPermissions($this->roles, $this->permissions),
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i:s'),
            'deleted_at' => $this->deleted_at?->format('d/m/Y H:i:s'),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by
        ];
    }

    /** @var Collection<Role> $roles */
    /** @var Collection<Permission> $permissions */
    protected function getPermissions($roles,  $permissions): array
    {
        $newPermissions = [];

        foreach ($roles as $role) {
            foreach ($role->permissions as $permission) {
                $newPermissions[] = $permission;
            }
        }

        foreach ($permissions as $permission) {
            $newPermissions[] = $permission;
        }

        return $newPermissions;
    }
    protected function permissionsAdministrador(): array
    {
        return [
            [
                'action' => 'manage',
                'subject' => 'all',
                'description' => 'Todas as permissões'
            ]
        ];
    }
}
