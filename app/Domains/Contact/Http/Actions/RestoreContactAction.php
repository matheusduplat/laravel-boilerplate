<?php

namespace App\Domains\Contact\Http\Actions;

use App\Domains\Contact\Enums\StatusContact;
use App\Domains\Contact\Model\Contact;
use Illuminate\Support\Facades\Auth;

class RestoreContactAction
{
    public function execute(Contact $contact)
    {
        $contact->update([
            'deleted_by' => null,
            'status' => StatusContact::WAITING
        ]);
        $contact->restore();
        return $contact;
    }
}
