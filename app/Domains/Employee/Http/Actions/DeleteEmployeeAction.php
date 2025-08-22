<?php

namespace App\Domains\Employee\Http\Actions;

use App\Domains\Employee\Enums\EmployeeStatus;
use App\Domains\Employee\Model\Employee;
use App\Domains\User\Model\User;
use Illuminate\Support\Facades\Auth;

class DeleteEmployeeAction
{
    public function execute(Employee $employee)
    {
        $employee->update([
            'status' => EmployeeStatus::INACTIVE,
            'deleted_by' => Auth::user()->name ?? null
        ]);
        $employee->delete();
        return $employee;
    }
}
