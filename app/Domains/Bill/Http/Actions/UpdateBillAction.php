<?php

namespace App\Domains\Bill\Http\Actions;

use App\Domains\Bill\Model\Bill;
use Illuminate\Support\Facades\Auth;

class UpdateBillAction
{
    public function execute(array $data, Bill $bill)
    {
        $data['updated_by'] = Auth::user()->name ?? null;
        $bill->update($data);
        return $bill;
    }
}
