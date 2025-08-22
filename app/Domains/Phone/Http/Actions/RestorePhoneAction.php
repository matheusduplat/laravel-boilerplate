<?php


namespace App\Domains\Phone\Http\Actions;

use App\Domains\Phone\Model\Phone;
use Illuminate\Support\Facades\Auth;

class RestorePhoneAction
{
    /** @var Collection<Phone> $phones */
    public function execute($phones)
    {
        foreach ($phones as $item) {
            $data['deleted_by'] = null;
            $item->update($data);
            $item->restore();
        }
        return $phones;
    }
}
