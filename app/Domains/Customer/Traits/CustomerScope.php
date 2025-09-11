<?php

namespace App\Domains\Customer\Traits;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait CustomerScope
{
    #[Scope]
    protected function relationWithTrashed(Builder $query): void
    {
        $query->with([
            'holder' => function ($query) {
                $query->withTrashed();
            },
            'phones' => function ($query) {
                $query->withTrashed();
            },
            'address' => function ($query) {
                $query->withTrashed();
            },
        ]);
    }
}
