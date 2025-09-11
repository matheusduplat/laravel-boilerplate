<?php

namespace App\Domains\IncomeReport\Http\Actions;

use App\Domains\Bill\Http\Resources\BillResource;
use App\Domains\Bill\Http\Resources\BillWithPaginationResource;
use App\Domains\Bill\Model\Bill;
use App\Domains\IncomeReport\Http\Resources\IncomeReportResource;
use App\Domains\IncomeReport\Http\Resources\IncomeReportWithPaginationResource;
use App\Domains\IncomeReport\Model\IncomeReport;

class IncomeReportAction
{
    public function query(array $data, bool $is_paginate)
    {
        $incomeReports = IncomeReport::query()
            ->relationWithTrashed()
            ->when(isset($data['customer_id']), function ($query) use ($data) {
                $query->where('customer_id', $data['customer_id']);
            })->when(isset($data['year']), function ($query) use ($data) {
                $query->where('year', $data['year']);
            });


        if ($is_paginate) {
            return   $incomeReports->paginate($data['per_page'] ?? 10);
        }
        return $incomeReports->get();
    }
    public function execute(array $data, bool $is_paginate)
    {
        $incomeReports = $this->query($data, $is_paginate);

        if ($is_paginate) {
            return new IncomeReportWithPaginationResource($incomeReports);
        }
        return IncomeReportResource::collection($incomeReports);
    }
}
