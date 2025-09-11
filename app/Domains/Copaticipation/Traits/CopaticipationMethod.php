<?php

namespace App\Domains\Copaticipation\Traits;


trait CopaticipationMethod
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
