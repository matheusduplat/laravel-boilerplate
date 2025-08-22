<?php

namespace App\Domains\Employee\Traits;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait EmployeeScope
{
    #[Scope]
    protected function relationWithTrashed(Builder $query): void
    {
        $query->with([
            'user' => function ($query) {
                $query->withTrashed();
            },
            'phones' => function ($query) {
                $query->withTrashed();
            }
        ]);
    }
}
