<?php

namespace App\Domains\IncomeReport\Http\Actions;

use App\Domains\IncomeReport\Model\IncomeReport;
use Illuminate\Support\Facades\Auth;

class RestoreIncomeReportAction
{
    public function execute(IncomeReport $incomeReport)
    {
        $incomeReport->update([
            'deleted_by' =>  null
        ]);
        $incomeReport->restore();
        return $incomeReport;
    }
}
