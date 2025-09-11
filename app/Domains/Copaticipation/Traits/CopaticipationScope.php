<?php

namespace App\Domains\Copaticipation\Traits;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait CopaticipationScope
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
