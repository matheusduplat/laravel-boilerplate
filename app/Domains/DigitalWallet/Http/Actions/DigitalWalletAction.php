<?php

namespace App\Domains\DigitalWallet\Http\Actions;

use App\Domains\DigitalWallet\Http\Resources\DigitalWalletResource;
use App\Domains\DigitalWallet\Http\Resources\DigitalWalletWithPaginationResource;
use App\Domains\DigitalWallet\Model\DigitalWallet;

class DigitalWalletAction
{
    public function query(array $data, bool $is_paginate)
    {
        $digitalWallets = DigitalWallet::query()
            ->relationWithTrashed()
            ->when(isset($data['customer_id']), function ($query) use ($data) {
                $query->where('customer_id', $data['customer_id']);
            })->when(isset($data['number']), function ($query) use ($data) {
                $query->where('number', 'like', "%{$data['number']}%");
            });

        if ($is_paginate) {
            return   $digitalWallets->paginate($data['per_page'] ?? 10);
        }
        return $digitalWallets->get();
    }
    public function execute(array $data, bool $is_paginate)
    {
        $digitalWallets = $this->query($data, $is_paginate);

        if ($is_paginate) {
            return new DigitalWalletWithPaginationResource($digitalWallets);
        }
        return DigitalWalletResource::collection($digitalWallets);
    }
}
