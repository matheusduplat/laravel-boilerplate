<?php

namespace App\Domains\User\Http\Resources;

use App\Domains\Address\Http\Resources\AddressResource;
use App\Domains\Customer\Http\Resources\CustomerResources;
use App\Domains\Customer\Model\Customer;
use App\Domains\Employee\Http\Resources\EmployeeResource;
use App\Domains\Employee\Model\Employee;
use App\Domains\Phone\Http\Resources\PhoneResource;
use App\Domains\Role\Http\Resource\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAuthResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->userable instanceof Customer) {
            $userable =  [
                'id' => $this->userable->id,
                'name' => $this->userable->name,
                'name_social' => $this->userable->name_social,
                'cpf' => $this->userable->cpf,
                'birth_date' => $this->userable->birth_date?->format('Y-m-d'),
                'birth_date_formated' => $this->userable->birth_date?->format('d/m/Y'),
                'status' => $this->userable->status,
                'status_translated' => $this->userable->status->label(),
                'address' => new AddressResource($this->userable->address),
                'phones' => PhoneResource::collection($this->userable->phones),
            ];
        }
        if ($this->userable instanceof Employee) {
            $userable = [
                'id' => $this->userable->id,
                'name' => $this->userable->name,
                'status' => $this->userable->status,
                'status_translated' => $this->userable->status->label(),
                'phones' => PhoneResource::collection($this->userable->phones),
            ];
        }
        $type = strtolower($this->userable_type);
        return [
            'id' => $this->id,
            'username' => $this->name,
            'email' => $this->email,
            'userable_id' => $this->userable_id,
            'userable_type' => $this->userable_type,
            "{$type}" => $userable,
            'roles' =>  RoleResource::collection($this->roles),
            'permissions' => $this->hasRole('Administrador') ? $this->permissionsAdministrador() : $this->getPermissions($this->roles, $this->permissions),
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
