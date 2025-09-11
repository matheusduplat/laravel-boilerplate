<?php

namespace App\Domains\DigitalWallet\Http\Controller;

use App\Domains\DigitalWallet\Http\Actions\DeleteDigitalWalletAction;
use App\Domains\DigitalWallet\Http\Actions\DigitalWalletAction;
use App\Domains\DigitalWallet\Http\Actions\RestoreDigitalWalletAction;
use App\Domains\DigitalWallet\Http\Actions\StoreDigitalWalletAction;
use App\Domains\DigitalWallet\Http\Actions\UpdateDigitalWalletAction;
use App\Domains\DigitalWallet\Http\Requests\DeleteDigitalWalletRequest;
use App\Domains\DigitalWallet\Http\Requests\DigitalWalletRequest;
use App\Domains\DigitalWallet\Http\Requests\DigitalWalletToCustomerRequest;
use App\Domains\DigitalWallet\Http\Requests\RestoreDigitalWalletRequest;
use App\Domains\DigitalWallet\Http\Requests\ShowDigitalWalletRequest;
use App\Domains\DigitalWallet\Http\Requests\StoreDigitalWalletRequest;
use App\Domains\DigitalWallet\Http\Requests\UpdateDigitalWalletRequest;
use App\Domains\DigitalWallet\Http\Resources\DigitalWalletResource;
use App\Domains\DigitalWallet\Model\DigitalWallet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DigitalWalletController extends Controller
{
    public function store(StoreDigitalWalletRequest $request, StoreDigitalWalletAction $storeDigitalWalletAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $storeDigitalWalletAction) {
            $storeDigitalWalletAction->execute($data);
        });
        return response()->json(['message' => __('Digital Wallet created successfully.')], 201);
    }
    public function update(UpdateDigitalWalletRequest $request, DigitalWallet $digitalWallet, UpdateDigitalWalletAction $updateDigitalWalletAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $digitalWallet, $updateDigitalWalletAction) {
            $updateDigitalWalletAction->execute($data, $digitalWallet);
        });
        return response()->json(['message' => __('Digital Wallet updated successfully.')], 201);
    }
    public function destroy(DeleteDigitalWalletRequest $request, DigitalWallet $digitalWallet, DeleteDigitalWalletAction $deleteDigitalWalletAction)
    {
        DB::transaction(function () use ($digitalWallet, $deleteDigitalWalletAction) {
            $deleteDigitalWalletAction->execute($digitalWallet);
        });
        return response()->json(['message' => __('Digital Wallet deleted successfully.')], 201);
    }
    public function show(ShowDigitalWalletRequest $request, DigitalWallet $digitalWallet)
    {
        $digitalWallet->relationLoadWithTrashed();
        $digitalWallet = new DigitalWalletResource($digitalWallet);
        return response()->json($digitalWallet);
    }
    public function restore(RestoreDigitalWalletRequest $request, DigitalWallet $digitalWallet, RestoreDigitalWalletAction $restoreDigitalWalletAction)
    {
        DB::transaction(function () use ($digitalWallet, $restoreDigitalWalletAction) {
            $restoreDigitalWalletAction->execute($digitalWallet);
        });
        return response()->json(['message' => __('Digital Wallet restored successfully.')], 201);
    }
    public function index(DigitalWalletRequest $request, DigitalWalletAction $digitalWalletAction)
    {
        $data = $request->validated();
        $digitalWallets = $digitalWalletAction->execute($data, false);
        return response()->json($digitalWallets);
    }

    public function withPagination(DigitalWalletRequest $request, DigitalWalletAction $digitalWalletAction)
    {
        $data = $request->validated();
        $digitalWallets = $digitalWalletAction->execute($data, true);
        return response()->json($digitalWallets);
    }
    public function toCustomer(DigitalWalletToCustomerRequest $request, DigitalWalletAction $digitalWalletAction)
    {
        $data['customer_id'] = Auth::user()->id;
        $digitalWallets = $digitalWalletAction->query($data, false);
        return response()->json($digitalWallets->first());
    }
}
