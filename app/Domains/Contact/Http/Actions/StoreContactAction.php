<?php

namespace App\Domains\Contact\Http\Actions;

use App\Domains\Contact\Enums\StatusContact;
use App\Domains\Contact\Model\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreContactAction
{
    public function execute(array $data)
    {

        return Contact::create([
            ...$data,
            "uuid" => Str::uuid(),
            'created_by' => Auth::user()->name ?? null,
            'status' => $data['status'] ?? StatusContact::WAITING
        ]);
    }
}
