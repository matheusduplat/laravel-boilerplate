<?php

namespace App\Domains\Bill\Http\Actions;

use App\Domains\Bill\Model\Bill;
use Illuminate\Support\Facades\Auth;

class StoreBillAction
{
    public function execute(array $data)
    {
        $data['created_by'] = Auth::user()->name ?? null;
        return Bill::create($data);
    }
}
