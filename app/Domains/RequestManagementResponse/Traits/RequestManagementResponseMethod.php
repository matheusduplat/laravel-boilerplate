<?php

namespace App\Domains\RequestManagementResponse\Traits;

trait RequestManagementResponseMethod
{
    public function relationLoadWithTrashed(): void
    {
        $this->loadMissing([
            'requestManagement' => function ($query) {
                $query->withTrashed();
            },

        ]);
    }
}
