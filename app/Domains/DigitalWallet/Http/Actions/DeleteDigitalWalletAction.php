<?php

namespace App\Domains\DigitalWallet\Http\Actions;

use App\Domains\DigitalWallet\Model\DigitalWallet;
use Illuminate\Support\Facades\Auth;

class DeleteDigitalWalletAction
{
    public function execute(DigitalWallet $digitalWallet)
    {
        $digitalWallet->update([
            'deleted_by' => Auth::user()->name ?? null
        ]);
        $digitalWallet->delete();
        return $digitalWallet;
    }
}
