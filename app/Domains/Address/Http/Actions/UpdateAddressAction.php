<?php

namespace App\Domains\Address\Http\Actions;

use App\Domains\Address\Model\Address;
use Illuminate\Support\Facades\Auth;

class UpdateAddressAction
{
    public function execute(array $data)
    {
        $address = Address::withTrashed()->find($data['id']);
        $data['updated_by'] = Auth::user()->name ?? null;
        $address = $address->update($data);
        return $address;
    }
}
