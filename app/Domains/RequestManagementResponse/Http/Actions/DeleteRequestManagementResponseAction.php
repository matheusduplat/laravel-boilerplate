<?php

namespace App\Domains\RequestManagementResponse\Http\Actions;

use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use Illuminate\Support\Facades\Auth;

class DeleteRequestManagementResponseAction
{

    public function execute(RequestManagementResponse $requestManagementResponse)
    {
        $requestManagementResponse->update(['deleted_by' => Auth::user()->name ?? null]);
        $requestManagementResponse->delete();
        return $requestManagementResponse;
    }
}
