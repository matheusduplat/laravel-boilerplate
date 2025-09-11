<?php

namespace App\Domains\RequestManagementResponse\Http\Controller;

use App\Domains\RequestManagementResponse\Http\Actions\DeleteRequestManagementResponseAction;
use App\Domains\RequestManagementResponse\Http\Actions\StoreRequestManagementResponseAction;
use App\Domains\RequestManagementResponse\Http\Requests\DeleteRequestManagementResponseRequest;
use App\Domains\RequestManagementResponse\Http\Requests\StoreRequestManagementResponseRequest;
use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RequestManagementResponseController extends Controller
{
    public function store(StoreRequestManagementResponseRequest $request, StoreRequestManagementResponseAction $storeRequestManagementResponseAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $storeRequestManagementResponseAction) {
            $storeRequestManagementResponseAction->execute($data);
        });

        return response()->json(['message' => __('RequestManagementResponse created successfully.')], 201);
    }
    public function destroy(DeleteRequestManagementResponseRequest $request, RequestManagementResponse $requestManagementResponse, DeleteRequestManagementResponseAction $deleteRequestManagementResponseAction)
    {
        DB::transaction(function () use ($deleteRequestManagementResponseAction, $requestManagementResponse) {
            $deleteRequestManagementResponseAction->execute($requestManagementResponse);
        });

        return response()->json(['message' => __('RequestManagementResponse deleted successfully.')], 201);
    }
}
