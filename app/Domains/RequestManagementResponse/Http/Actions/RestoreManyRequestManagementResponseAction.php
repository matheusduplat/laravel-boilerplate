<?php

namespace App\Domains\RequestManagementResponse\Http\Actions;

use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use Illuminate\Support\Facades\Auth;

class RestoreManyRequestManagementResponseAction
{

    public function execute(array $ids)
    {

        foreach ($ids as $id) {
            $requestManagementResponse = RequestManagementResponse::query()->withTrashed()->find($id);
            $requestManagementResponse->update(['deleted_by' =>  null]);
            $requestManagementResponse->restore();
        }
        return $requestManagementResponse;
    }
}
