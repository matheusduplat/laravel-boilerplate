<?php

namespace App\Domains\DigitalWallet\Http\Actions;

use App\Domains\DigitalWallet\Model\DigitalWallet;
use Illuminate\Support\Facades\Auth;

class UpdateDigitalWalletAction
{
    public function execute(array $data, DigitalWallet $digitalWallet)
    {
        $data['updated_by'] = Auth::user()->name ?? null;
        $digitalWallet->update($data);
        return $digitalWallet;
    }
}
