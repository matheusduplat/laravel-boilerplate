<?php

namespace App\Domains\State\Http\Actions;

use App\Domains\Customer\Http\Resources\CustomerResources;
use App\Domains\Customer\Http\Resources\CustomerWithPaginationResources;
use App\Domains\Customer\Model\Customer;
use App\Domains\State\Model\State;

class StateAction
{
    public function query(array $data, bool $is_paginate)
    {
        $customers = State::query()
            ->when(isset($data['name']), function ($query) use ($data) {
                $query->where('name', 'like', "%{$data['name']}%");
            });

        if ($is_paginate) {
            return   $customers->paginate($data['per_page'] ?? 10);
        }
        return $customers->get();
    }

    public function execute(array $data, bool $is_paginate)
    {
        $customers = $this->query($data, $is_paginate);
        if ($is_paginate) {
            return new CustomerWithPaginationResources($customers);
        }
        return CustomerResources::collection($customers);
    }
}
