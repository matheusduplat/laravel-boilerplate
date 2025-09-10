<?php

namespace App\Domains\Employee\Http\Actions;

use App\Domains\Employee\Http\Resources\EmployeeResource;
use App\Domains\Employee\Http\Resources\EmployeeWithPaginationResource;
use App\Domains\Employee\Model\Employee;

class EmployeeAction
{


    public function query(array $data, bool $is_paginate)
    {
        $employees = Employee::query()

            ->when(isset($data['name']), function ($query) use ($data) {
                $query->where('name', 'like', "%{$data['name']}%");
            })
            ->when(isset($data['email']), function ($query) use ($data) {
                $query->whereHas('user', function ($query) use ($data) {
                    $query->where('email', 'like', "%{$data['email']}%");
                });
            })
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status',  $data['status']);
            });


        if (isset($data['with_trashed']) && $data['with_trashed']) {
            $employees->withTrashed()->relationWithTrashed();
        } else {
            $employees->with(['user', 'phones']);
        }

        if ($is_paginate) {
            return $employees->paginate($data['per_page'] ?? 10);
        }
        return $employees->get();
    }


    public function execute(array $data, bool $is_paginate)
    {
        $employees = $this->query($data, $is_paginate);

        if ($is_paginate) {
            return new EmployeeWithPaginationResource($employees);
        }
        return EmployeeResource::collection($employees);
    }
}
