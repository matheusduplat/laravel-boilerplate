<?php

namespace App\Domains\Bill\Http\Actions;

use App\Domains\Bill\Model\Bill;
use Illuminate\Support\Facades\Auth;

class DeleteBillAction
{
    public function execute(Bill $bill)
    {
        $bill->update([
            'deleted_by' => Auth::user()->name ?? null
        ]);
        $bill->delete();
        return $bill;
    }
}
