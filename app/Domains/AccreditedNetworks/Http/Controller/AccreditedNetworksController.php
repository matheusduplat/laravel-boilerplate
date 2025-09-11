<?php

namespace App\Domains\AccreditedNetworks\Http\Controller;

use App\Domains\AccreditedNetworks\Http\Actions\AccreditedNetworksAction;
use App\Domains\AccreditedNetworks\Http\Actions\DeleteAccreditedNetworksAction;
use App\Domains\AccreditedNetworks\Http\Actions\RestoreAccreditedNetworksAction;
use App\Domains\AccreditedNetworks\Http\Actions\StoreAccreditedNetworksAction;
use App\Domains\AccreditedNetworks\Http\Actions\UpdateAccreditedNetworksAction;
use App\Domains\AccreditedNetworks\Http\Requests\AccreditedNetworksRequest;
use App\Domains\AccreditedNetworks\Http\Requests\DeleteAccreditedNetworksRequest;
use App\Domains\AccreditedNetworks\Http\Requests\RestoreAccreditedNetwordsRequest;
use App\Domains\AccreditedNetworks\Http\Requests\RestoreAccreditedNetworksRequest;
use App\Domains\AccreditedNetworks\Http\Requests\ShowAccreditedNetworksRequest;
use App\Domains\AccreditedNetworks\Http\Requests\StoreAccreditedNetworksRequest;
use App\Domains\AccreditedNetworks\Http\Requests\UpdateAccreditedNetwordsRequest;
use App\Domains\AccreditedNetworks\Http\Requests\UpdateAccreditedNetworksRequest;
use App\Domains\AccreditedNetworks\Http\Resources\AccreditedNetworksResource;
use App\Domains\AccreditedNetworks\Model\AccreditedNetworks;
use App\Domains\Address\Http\Actions\DeleteAddressAction;
use App\Domains\Address\Http\Actions\RestoreAddressAction;
use App\Domains\Address\Http\Actions\StoreAddressAction;
use App\Domains\Address\Http\Actions\UpdateAddressAction;
use App\Domains\Phone\Http\Actions\DeletePhoneAction;
use App\Domains\Phone\Http\Actions\RestorePhoneAction;
use App\Domains\Phone\Http\Actions\StorePhoneAction;
use App\Domains\Phone\Http\Actions\UpdatePhoneAction;
use App\Http\Controllers\Controller;
use DragonCode\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaravelLang\Publisher\Console\Update;

class AccreditedNetworksController extends Controller
{
    public function store(
        StoreAccreditedNetworksRequest $request,
        StoreAccreditedNetworksAction $storeAccreditedNetworksAction,
        StorePhoneAction $storePhoneAction,
        StoreAddressAction $storeAddressAction
    ) {
        $data = $request->validated();
        DB::transaction(function () use ($data, $storeAccreditedNetworksAction, $storePhoneAction, $storeAddressAction) {
            $accreditedNetworks = $storeAccreditedNetworksAction->execute($data);
            $storePhoneAction->execute($data['phones'], $accreditedNetworks);
            $storeAddressAction->execute($data['address'], $accreditedNetworks);
        });

        return response()->json(['message' => __('Accredited Network created successfully.')], 201);
    }
    public function update(
        UpdateAccreditedNetworksRequest $request,
        AccreditedNetworks $accreditedNetworks,
        UpdateAccreditedNetworksAction $updateAccreditedNetworksAction,
        StorePhoneAction $storePhoneAction,
        UpdatePhoneAction $updatePhoneAction,
        DeletePhoneAction $deletePhoneAction,
        UpdateAddressAction $updateAddressAction,
        StoreAddressAction $storeAddressAction

    ) {
        $data = $request->validated();

        DB::transaction(function () use ($data, $accreditedNetworks, $updateAccreditedNetworksAction, $storePhoneAction, $updatePhoneAction, $deletePhoneAction, $updateAddressAction, $storeAddressAction) {

            $updateAccreditedNetworksAction->execute($data, $accreditedNetworks);

            if (isset($data['address']['id'])) {
                $updateAddressAction->execute($data['address']);
            } else {
                $storeAddressAction->execute($data['address'], $accreditedNetworks);
            }

            $phonesEdit = collect($data['phones'])->filter(function ($phone) {
                return isset($phone['id']);
            });
            $phonesCreate = collect($data['phones'])->filter(function ($phone) {
                return !isset($phone['id']);
            });

            $updatePhoneAction->execute($phonesEdit, $accreditedNetworks);
            $storePhoneAction->execute($phonesCreate->toArray(), $accreditedNetworks);

            if (isset($data['delete_phones'])) {
                $deletePhoneAction->execute($data['delete_phones']);
            }
        });


        return response()->json(['message' => __('Accredited Network updated successfully.')], 201);
    }
    public function destroy(
        DeleteAccreditedNetworksRequest $request,
        AccreditedNetworks $accreditedNetworks,
        DeleteAccreditedNetworksAction $deleteAccreditedNetworksAction,
        DeletePhoneAction $deletePhoneAction,
        DeleteAddressAction $deleteAddressAction
    ) {

        DB::transaction(function () use ($accreditedNetworks, $deleteAccreditedNetworksAction, $deletePhoneAction, $deleteAddressAction) {
            $deleteAccreditedNetworksAction->execute($accreditedNetworks);
            $deletePhoneAction->execute($accreditedNetworks->phones->pluck('id')->toArray());
            $deleteAddressAction->execute($accreditedNetworks->address);
        });
        return response()->json(['message' => __('Accredited Network deleted successfully.')], 201);
    }

    public function restore(
        RestoreAccreditedNetworksRequest $request,
        AccreditedNetworks $accreditedNetworks,
        RestoreAccreditedNetworksAction $restoreAccreditedNetworksAction,
        RestoreAddressAction $restoreAddressAction,
        RestorePhoneAction $restorePhoneAction
    ) {
        DB::transaction(function () use ($accreditedNetworks, $restoreAccreditedNetworksAction, $restoreAddressAction, $restorePhoneAction) {
            $accreditedNetworks->relationLoadWithTrashed();
            $restoreAccreditedNetworksAction->execute($accreditedNetworks);
            $restoreAddressAction->execute($accreditedNetworks->address);
            $restorePhoneAction->execute($accreditedNetworks->phones);
        });
        return response()->json(['message' => __('Accredited Network restored successfully.')], 201);
    }
    public function index(AccreditedNetworksRequest $request, AccreditedNetworksAction $accreditedNetworksAction)
    {
        $data = $request->validated();
        $accreditedNetworks = $accreditedNetworksAction->execute($data, false);
        return response()->json($accreditedNetworks);
    }
    public function withPagination(AccreditedNetworksRequest $request, AccreditedNetworksAction $accreditedNetworksAction)
    {
        $data = $request->validated();
        $accreditedNetworks = $accreditedNetworksAction->execute($data, true);
        return response()->json($accreditedNetworks);
    }
    public function show(ShowAccreditedNetworksRequest $request, AccreditedNetworks $accreditedNetworks)
    {
        // $accreditedNetworks->relationLoadWithTrashed();
        $accreditedNetworks = new AccreditedNetworksResource($accreditedNetworks);
        return response()->json($accreditedNetworks);
    }
}
