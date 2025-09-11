<?php

namespace App\Domains\RequestManagementResponse\Http\Actions;

use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use Illuminate\Support\Facades\Auth;

class DeleteManyRequestManagementResponseAction
{

    public function execute(array $ids)
    {

        foreach ($ids as $id) {
            $requestManagementResponse = RequestManagementResponse::find($id);
            $requestManagementResponse->update(['deleted_by' => Auth::user()->name ?? null]);
            $requestManagementResponse->delete();
        }
        return $requestManagementResponse;
    }
}
