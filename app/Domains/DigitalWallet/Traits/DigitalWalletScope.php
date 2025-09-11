<?php

namespace App\Domains\DigitalWallet\Traits;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait DigitalWalletScope
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
