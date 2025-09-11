<?php

namespace App\Domains\IncomeReport\Traits;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait IncomeReportScope
{
    #[Scope]
    protected function relationWithTrashed(Builder $query): void
    {
        $query->with([
            'customer' => function ($query) {
                $query->withTrashed();
            },

        ]);
    }
}
