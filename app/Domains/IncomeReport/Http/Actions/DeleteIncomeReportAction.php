<?php

namespace App\Domains\IncomeReport\Http\Actions;

use App\Domains\Bill\Model\Bill;
use App\Domains\IncomeReport\Model\IncomeReport;
use Illuminate\Support\Facades\Auth;

class DeleteIncomeReportAction
{
    public function execute(IncomeReport $incomeReport)
    {
        $incomeReport->update([
            'deleted_by' => Auth::user()->name ?? null
        ]);
        $incomeReport->delete();
        return $incomeReport;
    }
}
