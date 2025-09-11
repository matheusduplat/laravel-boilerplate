<?php

namespace App\Domains\AccreditedNetworks\Http\Actions;

use App\Domains\AccreditedNetworks\Model\AccreditedNetworks;
use Illuminate\Support\Facades\Auth;

class DeleteAccreditedNetworksAction
{
    public function execute(AccreditedNetworks $accreditedNetworks)
    {
        $accreditedNetworks->update([
            'deleted_by' => Auth::user()->name ?? null
        ]);
        $accreditedNetworks->delete();
        return $accreditedNetworks;
    }
}
