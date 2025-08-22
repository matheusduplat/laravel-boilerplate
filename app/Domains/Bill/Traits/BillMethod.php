<?php

namespace App\Domains\Bill\Traits;

use App\Domains\Audit\Service\AuditService;

trait BillMethod
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
