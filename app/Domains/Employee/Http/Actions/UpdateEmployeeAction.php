<?php

namespace App\Domains\Employee\Http\Actions;

use App\Domains\Employee\Enums\EmployeeStatus;
use App\Domains\Employee\Model\Employee;
use App\Domains\User\Model\User;
use Illuminate\Support\Facades\Auth;

class UpdateEmployeeAction
{
    public function execute(Employee $employee, array $data)
    {
        $data['updated_by'] = Auth::user()->name ?? null;
        $employee = $employee->update($data);
        return $employee;
    }
}
