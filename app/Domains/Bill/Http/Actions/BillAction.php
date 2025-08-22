<?php

namespace App\Domains\Bill\Http\Actions;

use App\Domains\Bill\Http\Resources\BillResource;
use App\Domains\Bill\Http\Resources\BillWithPaginationResource;
use App\Domains\Bill\Model\Bill;

class BillAction
{
    public function query(array $data, bool $is_paginate)
    {
        $bills = Bill::query()
            ->when(isset($data['base_month']), function ($query) use ($data) {
                $query->where('base_month', $data['base_month']);
            })->when(isset($data['base_year']), function ($query) use ($data) {
                $query->where('base_year', $data['base_year']);
            })->when(isset($data['customer_id']), function ($query) use ($data) {
                $query->where('customer_id', $data['customer_id']);
            });

        if (isset($data['with_trashed'])) {
            if ($data['with_trashed']) {
                $bills->withTrashed()->relationWithTrashed();
            } else {
                $bills->with(['customer']);
            }
        }

        if ($is_paginate) {
            return   $bills->paginate($data['per_page'] ?? 10);
        }
        return $bills->get();
    }
    public function execute(array $data, bool $is_paginate)
    {
        $bills = $this->query($data, $is_paginate);

        if ($is_paginate) {
            return new BillWithPaginationResource($bills);
        }
        return BillResource::collection($bills);
    }
}
