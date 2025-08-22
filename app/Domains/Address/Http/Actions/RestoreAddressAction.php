<?php

namespace App\Domains\Address\Http\Actions;

use App\Domains\Address\Model\Address;
use Illuminate\Support\Facades\Auth;

class RestoreAddressAction
{
    public function execute(?Address $address)
    {
        $address?->update([
            'deleted_by' => null
        ]);
        $address?->restore();
        return $address;
    }
}
