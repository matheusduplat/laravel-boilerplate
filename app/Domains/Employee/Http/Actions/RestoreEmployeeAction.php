<?php

namespace App\Domains\Employee\Http\Actions;

use App\Domains\Employee\Enums\EmployeeStatus;
use App\Domains\Employee\Model\Employee;
use App\Domains\User\Model\User;
use Illuminate\Support\Facades\Auth;

class RestoreEmployeeAction
{
    public function execute(Employee $employee)
    {
        $employee->update([
            'status' => EmployeeStatus::ACTIVE,
            'deleted_by' => null
        ]);
        $employee->restore();
        return $employee;
    }
}
