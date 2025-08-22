<?php


namespace App\Domains\Phone\Http\Actions;

use App\Domains\Phone\Model\Phone;
use Illuminate\Support\Facades\Auth;

class DeletePhoneAction
{

    public function execute(array $phones)
    {
        foreach ($phones as $item) {
            $phone = Phone::withTrashed()->find($item);
            $phone->update(['deleted_by' => Auth::user()->name ?? null]);
            $phone->delete();
        }
        return $phones;
    }
}
