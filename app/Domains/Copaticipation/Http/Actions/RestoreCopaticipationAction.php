<?php

namespace App\Domains\Copaticipation\Http\Actions;

use App\Domains\Copaticipation\Model\Copaticipation;
use App\Domains\IncomeReport\Model\IncomeReport;
use Illuminate\Support\Facades\Auth;

class RestoreCopaticipationAction
{
    public function execute(Copaticipation $copaticipation)
    {
        $copaticipation->update([
            'deleted_by' =>  null
        ]);
        $copaticipation->restore();
        return $copaticipation;
    }
}
