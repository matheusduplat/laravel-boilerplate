<?php

namespace App\Domains\Permission\Http\Actions;

use App\Domains\Permission\Model\Permission;

class PermissionAction
{
    public function query(array $data, bool $is_paginate)
    {
        $permissions = Permission::query()
            ->when(isset($data['name']), function ($query) use ($data) {
                $query->where('description', 'like', "%{$data['name']}%");
            });
        if ($is_paginate) {
            return   $permissions->paginate($data['per_page'] ?? 10);
        }
        return $permissions->get();
    }

    public function execute(array $data, bool $is_paginate)
    {
        $permissions = $this->query($data, $is_paginate);
        // if ($is_paginate) {
        //     return new RoleWithPaginationResource($permissions);
        // }
        // return RoleResource::collection($permissions);
    }
}
