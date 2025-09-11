<?php

namespace App\Domains\Copaticipation\Http\Actions;

use App\Domains\Copaticipation\Model\Copaticipation;
use Illuminate\Support\Facades\Auth;

class StoreCopaticipationAction
{
    public function execute(array $data)
    {
        $data['created_by'] = Auth::user()->name ?? null;
        return Copaticipation::create($data);
    }
}
