<?php

namespace App\Domains\RequestManagement\Http\Actions;


use App\Domains\RequestManagement\Http\Resources\RequestManagementResource;
use App\Domains\RequestManagement\Http\Resources\RequestManagementWithPaginationResource;
use App\Domains\RequestManagement\Model\RequestManagement;
use Illuminate\Support\Str;

class RequestManagementAction
{
    public function execute(array $data, bool $is_paginate)
    {
        $requestManagement = RequestManagement::query()
            ->when(isset($data['type']), function ($query) use ($data) {
                $query->where('type',  $data['type']);
            })
            ->when(isset($data['customer_id']), function ($query) use ($data) {
                $query->where('customer_id',  $data['customer_id']);
            })
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status',  $data['status']);
            })->when(isset($data['code']), function ($query) use ($data) {
                $query->where('code', "LIKE", "%{$data['code']}%");
            });

        if ($data['with_trashed']) {
            $requestManagement->withTrashed()->relationWithTrashed();
        } else {
            $requestManagement->with(['responses', 'customer']);
        }

        if ($is_paginate) {
            $requestManagement = $requestManagement->paginate($data['per_page'] ?? 10);
            return new RequestManagementWithPaginationResource($requestManagement);
        }
        $requestManagement =  $requestManagement->get();
        return RequestManagementResource::collection($requestManagement);
    }
}
