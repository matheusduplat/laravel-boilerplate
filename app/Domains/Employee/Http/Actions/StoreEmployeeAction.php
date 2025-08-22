<?php

namespace App\Domains\Employee\Http\Actions;

use App\Domains\Employee\Enums\EmployeeStatus;
use App\Domains\Employee\Model\Employee;
use App\Domains\User\Model\User;
use Illuminate\Support\Facades\Auth;

class StoreEmployeeAction
{
    public function execute(array $data)
    {
        $data['created_by'] = Auth::user()->name ?? null;
        $data['status'] =  EmployeeStatus::ACTIVE;
        $employee = Employee::create($data);
        return $employee;
    }
}
