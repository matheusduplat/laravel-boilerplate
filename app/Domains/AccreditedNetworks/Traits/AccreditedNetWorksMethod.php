<?php

namespace App\Domains\AccreditedNetWorks\Traits;

trait AccreditedNetWorksMethod
{
    public function relationLoadWithTrashed(): void
    {
        $this->loadMissing([
            'phones' => function ($query) {
                $query->withTrashed();
            },
            'address' => function ($query) {
                $query->withTrashed()->with('stateable');
            },
        ]);
    }
}
