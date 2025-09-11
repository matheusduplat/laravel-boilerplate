<?php

namespace App\Domains\DigitalWallet\Traits;

use App\Domains\Audit\Service\AuditService;

trait DigitalWalletMethod
{
    public function relationLoadWithTrashed(): void
    {
        $this->loadMissing([
            'customer' => function ($query) {
                $query->withTrashed();
            },

        ]);
    }
}
