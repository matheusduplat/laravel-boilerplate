<?php

namespace App\Domains\AccreditedNetworks\Http\Actions;

use App\Domains\AccreditedNetworks\Model\AccreditedNetworks;
use Illuminate\Support\Facades\Auth;

class UpdateAccreditedNetworksAction
{
    public function execute(array $data, AccreditedNetworks $accreditedNetworks)
    {
        $data['updated_by'] = Auth::user()->name ?? null;
        $accreditedNetworks->update($data);
        return $accreditedNetworks;
    }
}
