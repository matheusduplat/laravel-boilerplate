<?php

namespace App\Domains\AccreditedNetworks\Http\Actions;

use App\Domains\AccreditedNetworks\Model\AccreditedNetworks;
use Illuminate\Support\Facades\Auth;

class RestoreAccreditedNetworksAction
{
    public function execute(AccreditedNetworks $accreditedNetworks)
    {
        $accreditedNetworks->update([
            'deleted_by' =>  null
        ]);
        $accreditedNetworks->restore();
        return $accreditedNetworks;
    }
}
