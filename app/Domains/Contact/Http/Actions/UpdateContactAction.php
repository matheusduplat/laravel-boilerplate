<?php

namespace App\Domains\Contact\Http\Actions;

use App\Domains\Contact\Model\Contact;
use Illuminate\Support\Facades\Auth;

class UpdateContactAction
{
    public function execute(array $data, Contact $contact)
    {
        $contact->update([
            ...$data,
            'updated_by' => Auth::user()->name ?? null
        ]);

        return $contact;
    }
}
