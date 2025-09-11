<?php

namespace App\Domains\AccreditedNetworks\Http\Actions;

use App\Domains\AccreditedNetworks\Http\Resources\AccreditedNetworksResource;
use App\Domains\AccreditedNetworks\Http\Resources\AccreditedNetworksWithPaginationResource;
use App\Domains\AccreditedNetworks\Model\AccreditedNetworks;


class AccreditedNetworksAction
{
    public function query(array $data, bool $is_paginate)
    {
        $accreditedNetworks = AccreditedNetworks::query()
            ->when(isset($data['name']), function ($query) use ($data) {
                $query->where('name', 'like', "%{$data['name']}%");
            })->when(isset($data['type_service']), function ($query) use ($data) {
                $query->where('type_service', $data['type_service']);
            })
            ->when(isset($data['city']), function ($query) use ($data) {
                $query->whereHas('address', function ($query) use ($data) {
                    $query->where('city', 'like', "%{$data['city']}%");
                });
            })->when(isset($data['state']), function ($query) use ($data) {
                $query->whereHas('address', function ($query) use ($data) {
                    $query->where('state', $data['state']);
                });
            });

        if ($is_paginate) {
            return   $accreditedNetworks->paginate($data['per_page'] ?? 10);
        }
        return $accreditedNetworks->get();
    }
    public function execute(array $data, bool $is_paginate)
    {
        $accreditedNetworks = $this->query($data, $is_paginate);

        if ($is_paginate) {
            return new AccreditedNetworksWithPaginationResource($accreditedNetworks);
        }
        return AccreditedNetworksResource::collection($accreditedNetworks);
    }
}
