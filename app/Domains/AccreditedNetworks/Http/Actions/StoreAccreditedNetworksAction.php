<?php

namespace App\Domains\AccreditedNetworks\Http\Actions;

use App\Domains\AccreditedNetworks\Model\AccreditedNetworks;
use Illuminate\Support\Facades\Auth;

class StoreAccreditedNetworksAction
{
    public function execute(array $data)
    {
        $data['created_by'] = Auth::user()->name ?? null;
        return AccreditedNetworks::create($data);
    }
}
