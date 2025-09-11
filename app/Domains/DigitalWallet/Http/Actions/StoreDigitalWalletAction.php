<?php

namespace App\Domains\DigitalWallet\Http\Actions;

use App\Domains\DigitalWallet\Model\DigitalWallet;
use Illuminate\Support\Facades\Auth;

class StoreDigitalWalletAction
{
    public function execute(array $data)
    {
        $data['created_by'] = Auth::user()->name ?? null;
        return DigitalWallet::create($data);
    }
}
