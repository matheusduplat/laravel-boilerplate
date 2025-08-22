<?php

namespace App\Domains\Role\Http\Actions;

use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\Role\Http\Resource\RoleResource;
use App\Domains\Role\Http\Resource\RoleWithPaginationResource;
use App\Domains\Role\Model\Role;

class RoleAction
{
    public function query(array $data, bool $is_paginate)
    {
        $roles = Role::query()
            ->whereNot('name', RoleDefaults::CLIENT)
            ->when(isset($data['name']), function ($query) use ($data) {
                $query->where('name', 'like', "%{$data['name']}%");
            });

        if (isset($data['with_trashed'])) {
            if ($data['with_trashed']) {
                $roles->withTrashed();
            } else {
                $roles->with('permissions');
            }
        }

        if ($is_paginate) {
            return   $roles->paginate($data['per_page'] ?? 10);
        }
        return $roles->get();
    }

    public function execute(array $data, bool $is_paginate)
    {
        $roles = $this->query($data, $is_paginate);
        if ($is_paginate) {
            return new RoleWithPaginationResource($roles);
        }
        return RoleResource::collection($roles);
    }
}
