<?php

namespace App\Domains\Contact\Traits;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait ContactScope
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
