<?php

namespace App\Domains\Address\Http\Actions;

use Illuminate\Support\Facades\Auth;

class StoreAddressAction
{
    public function execute(array $data, $model)
    {
        $data['created_by'] = Auth::user()->name ?? null;
        $address = $model->address()->create($data);
        return $address;
    }
}
