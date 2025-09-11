<?php

namespace App\Domains\Bill\Traits;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait BillScope
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
