<?php

namespace App\Domains\IncomeReport\Http\Actions;

use App\Domains\IncomeReport\Model\IncomeReport;
use Illuminate\Support\Facades\Auth;

class UpdateIncomeReportAction
{
    public function execute(array $data, IncomeReport $incomeReport)
    {
        $data['updated_by'] = Auth::user()->name ?? null;
        $incomeReport->update($data);
        return $incomeReport;
    }
}
