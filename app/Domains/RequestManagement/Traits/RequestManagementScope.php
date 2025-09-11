<?php

namespace App\Domains\RequestManagement\Traits;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait RequestManagementScope
{
    #[Scope]
    protected function relationWithTrashed(Builder $query): void
    {
        $query->with([
            'responses' => function ($query) {
                $query->withTrashed();
            },
            'customer' => function ($query) {
                $query->withTrashed();
            },
        ]);
    }
}
