<?php

namespace App\Domains\Employee\Http\Actions;

use App\Domains\Employee\Http\Resources\EmployeeResource;
use App\Domains\Employee\Http\Resources\EmployeeWithPaginationResource;
use App\Domains\Employee\Model\Employee;

class EmployeeAction
{
    public function execute(array $data, bool $is_paginate)
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
        if ($data['with_trashed']) {
            $employees->withTrashed()->relationWithTrashed();
        } else {
            $employees->with(['user', 'phones']);
        }

        if ($is_paginate) {
            $employees = $employees->paginate($data['per_page'] ?? 10);
            return new EmployeeWithPaginationResource($employees);
        }
        $employees =  $employees->get();
        return EmployeeResource::collection($employees);
    }
}
