<?php

namespace App\Domains\Contact\Traits;


trait ContactMethod
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
