<?php

namespace App\Domains\RequestManagement\Http\Controller;

use App\Domains\RequestManagement\Http\Actions\RequestManagementAction;
use App\Domains\RequestManagement\Http\Actions\DeleteRequestManagementAction;
use App\Domains\RequestManagement\Http\Actions\NotificationRequestManagementAction;
use App\Domains\RequestManagement\Http\Actions\RestoreRequestManagementAction;
use App\Domains\RequestManagement\Http\Actions\StoreRequestManagementAction;
use App\Domains\RequestManagement\Http\Actions\UpdateRequestManagementAction;
use App\Domains\RequestManagement\Http\Requests\DeleteRequestManagementRequest;
use App\Domains\RequestManagement\Http\Requests\RequestManagementRequest;
use App\Domains\RequestManagement\Http\Requests\RequestManagementToCustomerRequest;
use App\Domains\RequestManagement\Http\Requests\RestoreRequestManagementRequest;
use App\Domains\RequestManagement\Http\Requests\ShowRequestManagementRequest;
use App\Domains\RequestManagement\Http\Requests\StoreRequestManagementRequest;
use App\Domains\RequestManagement\Http\Requests\ToCustomerRequestManagementRequest;
use App\Domains\RequestManagement\Http\Requests\UpdateRequestManagementRequest;
use App\Domains\RequestManagement\Http\Resources\RequestManagementResource;
use App\Domains\RequestManagement\Model\RequestManagement;
use App\Domains\RequestManagementResponse\Http\Actions\DeleteManyRequestManagementResponseAction;
use App\Domains\RequestManagementResponse\Http\Actions\DeleteRequestManagementResponseAction;
use App\Domains\RequestManagementResponse\Http\Actions\RestoreManyRequestManagementResponseAction;
use App\Domains\RequestManagementResponse\Http\Actions\StoreRequestManagementResponseAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RequestManagementRequest $request, RequestManagementAction $requestManagementAction)
    {
        $data = $request->validated();
        $requestManagements = $requestManagementAction->execute($data, false);
        return response()->json($requestManagements);
    }

    /**
     * Display a listing of the resource.
     */
    public function withPagination(RequestManagementRequest $request, RequestManagementAction $requestManagementAction)
    {
        $data = $request->validated();
        $requestManagements = $requestManagementAction->execute($data, true);
        return response()->json($requestManagements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequestManagementRequest $request, StoreRequestManagementAction $storeRequestManagementAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $storeRequestManagementAction) {
            $storeRequestManagementAction->execute($data);
        });
        return response()->json(['message' => __('Request Management created successfully.')], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowRequestManagementRequest $request, RequestManagement $requestManagement)
    {
        $requestManagement = new RequestManagementResource($requestManagement);
        return response()->json($requestManagement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateRequestManagementRequest $request,
        RequestManagement $requestManagement,
        UpdateRequestManagementAction $updateRequestManagementAction,
        StoreRequestManagementResponseAction $storeRequestManagementResponseAction,
        DeleteManyRequestManagementResponseAction $deleteManyRequestManagementResponseAction,
        NotificationRequestManagementAction $notificationRequestManagementAction
    ) {
        $data = $request->validated();

        DB::transaction(
            function ()
            use ($data, $requestManagement, $updateRequestManagementAction, $storeRequestManagementResponseAction, $deleteManyRequestManagementResponseAction, $notificationRequestManagementAction) {
                $updateRequestManagementAction->execute($data, $requestManagement);

                if (isset($data['response'])) {
                    $data['response']['request_management_id'] = $requestManagement->id;
                    $storeRequestManagementResponseAction->execute($data['response']);
                }

                if (isset($data['delete_response'])) {
                    $deleteManyRequestManagementResponseAction->execute($data['delete_response']);
                }

                $notificationRequestManagementAction->execute($data, $requestManagement);
            }
        );
        return response()->json(['message' => __('Request Management updated successfully.')], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        DeleteRequestManagementRequest $request,
        RequestManagement $requestManagement,
        DeleteRequestManagementAction $deleteRequestManagementAction,
        DeleteManyRequestManagementResponseAction $deleteManyRequestManagementResponseAction
    ) {
        DB::transaction(function () use ($requestManagement, $deleteRequestManagementAction, $deleteManyRequestManagementResponseAction) {
            $deleteRequestManagementAction->execute($requestManagement);
            $deleteManyRequestManagementResponseAction->execute($requestManagement->responses->pluck('id')->toArray());
        });

        return response()->json(['message' => __('Request Management deleted successfully.')], 201);
    }

    public function restore(
        RestoreRequestManagementRequest $request,
        RequestManagement $requestManagement,
        RestoreRequestManagementAction $restoreRequestManagementAction,
        RestoreManyRequestManagementResponseAction $restoreManyRequestManagementResponseAction
    ) {
        DB::transaction(function () use ($requestManagement, $restoreRequestManagementAction, $restoreManyRequestManagementResponseAction) {
            $requestManagement->relationLoadWithTrashed();
            $restoreRequestManagementAction->execute($requestManagement);
            $restoreManyRequestManagementResponseAction->execute($requestManagement->responses->pluck('id')->toArray());
        });
        return response()->json(['message' => __('Request Management restored successfully.')], 201);
    }
    /**
     * Display a listing of the resource.
     */
    public function toCustomer(RequestManagementToCustomerRequest $request, RequestManagementAction $requestManagementAction)
    {
        $data = $request->validated();
        $requestManagements = $requestManagementAction->execute($data, true);
        return response()->json($requestManagements);
    }
}
