<?php

namespace App\Domains\DigitalWallet\Http\Actions;

use App\Domains\DigitalWallet\Model\DigitalWallet;
use Illuminate\Support\Facades\Auth;

class RestoreDigitalWalletAction
{
    public function execute(DigitalWallet $digitalWallet)
    {
        $digitalWallet->update([
            'deleted_by' =>  null
        ]);
        $digitalWallet->restore();
        return $digitalWallet;
    }
}
