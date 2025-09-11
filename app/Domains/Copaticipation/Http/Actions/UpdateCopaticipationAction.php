<?php

namespace App\Domains\Copaticipation\Http\Actions;

use App\Domains\Copaticipation\Model\Copaticipation;
use App\Domains\IncomeReport\Model\IncomeReport;
use Illuminate\Support\Facades\Auth;

class UpdateCopaticipationAction
{
    public function execute(array $data, Copaticipation $copaticipation)
    {
        $data['updated_by'] = Auth::user()->name ?? null;
        $copaticipation->update($data);
        return $copaticipation;
    }
}
