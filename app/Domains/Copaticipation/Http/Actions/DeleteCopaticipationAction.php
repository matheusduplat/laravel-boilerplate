<?php

namespace App\Domains\Copaticipation\Http\Actions;

use App\Domains\Copaticipation\Model\Copaticipation;
use Illuminate\Support\Facades\Auth;

class DeleteCopaticipationAction
{
    public function execute(Copaticipation $copaticipation)
    {
        $copaticipation->update([
            'deleted_by' => Auth::user()->name ?? null
        ]);
        $copaticipation->delete();
        return $copaticipation;
    }
}
