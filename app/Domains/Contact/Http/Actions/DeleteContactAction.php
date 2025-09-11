<?php

namespace App\Domains\Contact\Http\Actions;

use App\Domains\Contact\Enums\StatusContact;
use App\Domains\Contact\Model\Contact;
use Illuminate\Support\Facades\Auth;

class DeleteContactAction
{
    public function execute(Contact $contact)
    {
        $contact->update([
            'deleted_by' => Auth::user()->name ?? null,
            'status' => StatusContact::CANCELLED
        ]);
        $contact->delete();
        return $contact;
    }
}
