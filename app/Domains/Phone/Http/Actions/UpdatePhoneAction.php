<?php


namespace App\Domains\Phone\Http\Actions;

use App\Domains\Phone\Model\Phone;
use Illuminate\Support\Facades\Auth;

class UpdatePhoneAction
{
    /** @var Collection $phones */
    public function execute($phones, $model)
    {
        foreach ($phones as $item) {
            $item['updated_by'] = Auth::user()->name ?? null;
            $phone = $model->phones()->find($item['id']);
            if (!$phone) {
                continue;
            }
            $phone->update($item);
        }
        return $phones;
    }
}
