<?php

namespace App\Domains\Contact\Http\Controller;

use App\Domains\Contact\Http\Actions\DeleteContactAction;
use App\Domains\Contact\Http\Actions\RestoreContactAction;
use App\Domains\Contact\Http\Actions\StoreContactAction;
use App\Domains\Contact\Http\Actions\UpdateContactAction;
use App\Domains\Contact\Http\Requests\DeleteContactRequest;
use App\Domains\Contact\Http\Requests\RestoreContactRequest;
use App\Domains\Contact\Http\Requests\StoreContactRequest;
use App\Domains\Contact\Http\Requests\UpdateContactRequest;
use App\Domains\Contact\Model\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request, StoreContactAction $storeContactAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $storeContactAction) {
            $storeContactAction->execute($data);
        });

        return response()->json(['message' => __('Contact created successfully.')], 201);
    }
    public function update(UpdateContactRequest $request, Contact $contact, UpdateContactAction $updateContactAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $contact, $updateContactAction) {
            $updateContactAction->execute($data, $contact);
        });
        return response()->json(['message' => __('Contact updated successfully.')], 201);
    }
    public function destroy(DeleteContactRequest $request, Contact $contact, DeleteContactAction $deleteContactAction)
    {
        DB::transaction(function () use ($contact, $deleteContactAction) {
            $deleteContactAction->execute($contact);
        });
        return response()->json(['message' => __('Contact deleted successfully.')], 201);
    }
    public function restore(RestoreContactRequest $request, Contact $contact, RestoreContactAction $restoreContactAction)
    {
        DB::transaction(function () use ($contact, $restoreContactAction) {
            $restoreContactAction->execute($contact);
        });
        return response()->json(['message' => __('Contact restored successfully.')], 201);
    }
}
