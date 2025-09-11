<?php

namespace App\Domains\IncomeReport\Http\Actions;

use App\Domains\IncomeReport\Model\IncomeReport;
use Illuminate\Support\Facades\Auth;

class StoreIncomeReportAction
{
    public function execute(array $data)
    {
        $data['created_by'] = Auth::user()->name ?? null;
        return IncomeReport::create($data);
    }
}
