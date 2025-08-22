<?php


namespace App\Domains\Phone\Http\Actions;

use Illuminate\Support\Facades\Auth;

class StorePhoneAction
{
    public function execute(array $phones, $model)
    {
        foreach ($phones as  $phone) {
            $phone['created_by'] = Auth::user()->name ?? null;
            $model->phones()->create($phone);
        }

        return;
    }
}
