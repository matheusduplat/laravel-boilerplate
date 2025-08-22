<?php

namespace App\Domains\Employee\Traits;

trait EmployeeMethod
{
    public function relationLoadWithTrashed(): void
    {
        $this->loadMissing([
            'user' => function ($query) {
                $query->withTrashed();
            },
            'phones' => function ($query) {
                $query->withTrashed();
            }
        ]);
    }
}
