<?php

namespace App\Domains\RequestManagementResponse\Traits;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait RequestManagementResponseScope
{
    #[Scope]
    protected function relationWithTrashed(Builder $query): void
    {
        $query->with([
            'requestManagement' => function ($query) {
                $query->withTrashed();
            },

        ]);
    }
}
