<?php

namespace App\Domains\RequestManagementResponse\Http\Actions;

use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use Illuminate\Support\Facades\Auth;

class StoreRequestManagementResponseAction
{

    public function execute(array $data)
    {
        $data = [
            ...$data,
            'created_by' => Auth::user()->name ?? null,
            'date_hour' => now(),
            'authorable_id' => Auth::user()->id,
            'authorable_type' => Auth::user()->getMorphClass(),
        ];
        return RequestManagementResponse::create($data);
    }
}
