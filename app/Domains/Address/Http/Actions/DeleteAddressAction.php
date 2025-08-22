<?php

namespace App\Domains\Address\Http\Actions;

use App\Domains\Address\Model\Address;
use Illuminate\Support\Facades\Auth;

class DeleteAddressAction
{
    public function execute(?Address $address)
    {
        $address?->update([
            'deleted_by' => Auth::user()->name ?? null
        ]);
        $address?->delete();
        return $address;
    }
}
