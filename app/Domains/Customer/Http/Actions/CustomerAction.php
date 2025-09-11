<?php

namespace App\Domains\Customer\Http\Actions;

use App\Domains\Customer\Http\Resources\CustomerResources;
use App\Domains\Customer\Http\Resources\CustomerWithPaginationResources;
use App\Domains\Customer\Model\Customer;


class CustomerAction
{
    public function query(array $data, bool $is_paginate)
    {
        $customers = Customer::query()
            ->when(isset($data['name']), function ($query) use ($data) {
                $query->where('name', 'like', "%{$data['name']}%");
            })->when(isset($data['cpf']), function ($query) use ($data) {
                $query->where('cpf', 'like', "%{$data['cpf']}%");
            })->when(isset($data['email']), function ($query) use ($data) {
                $query->where('email',  'like', "%{$data['email']}%");
            })->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status',  $data['status']);
            });

        if (isset($data['with_trashed'])) {

            if ($data['with_trashed']) {
                $customers->withTrashed()->relationWithTrashed();
            } else {
                $customers->with(['holder', 'phones', 'address.stateable']);
            }
        }

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
