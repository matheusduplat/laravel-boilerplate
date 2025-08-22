<?php

namespace App\Domains\Bill\Http\Actions;

use App\Domains\Bill\Model\Bill;
use Illuminate\Support\Facades\Auth;

class RestoreBillAction
{
    public function execute(Bill $bill)
    {
        $bill->update([
            'deleted_by' =>  null
        ]);
        $bill->restore();
        return $bill;
    }
}
