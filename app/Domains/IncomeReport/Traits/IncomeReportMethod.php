<?php

namespace App\Domains\IncomeReport\Traits;


trait IncomeReportMethod
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
