<?php

namespace App\Domains\Customer\Http\Controller;

use App\Domains\Address\Http\Actions\DeleteAddressAction;
use App\Domains\Address\Http\Actions\RestoreAddressAction;
use App\Domains\Address\Http\Actions\StoreAddressAction;
use App\Domains\Address\Http\Actions\UpdateAddressAction;
use App\Domains\Customer\Http\Actions\CustomerAction;
use App\Domains\Customer\Http\Actions\DeleteCustomerAction;
use App\Domains\Customer\Http\Actions\RestoreCustomerAction;
use App\Domains\Customer\Http\Actions\StoreCustomerAction;
use App\Domains\Customer\Http\Actions\UpdateCustomerAction;
use App\Domains\Customer\Http\Requests\ComponentSelectCustomerRequest;
use App\Domains\Customer\Http\Requests\CreatePasswordRequest;
use App\Domains\Customer\Http\Requests\CustomerRequest;
use App\Domains\Customer\Http\Requests\DeleteCustomerRequest;
use App\Domains\Customer\Http\Requests\RestoreCustomerRequest;
use App\Domains\Customer\Http\Requests\ShowCustomerRequest;
use App\Domains\Customer\Http\Requests\StoreCustomerRequest;
use App\Domains\Customer\Http\Requests\UpdateAddressCustomerRequest;
use App\Domains\Customer\Http\Requests\UpdateCustomerRequest;
use App\Domains\Customer\Http\Requests\UpdatePasswordCustomerRequest;
use App\Domains\Customer\Http\Requests\UpdatePhoneCustomerRequest;
use App\Domains\Customer\Http\Requests\UpdateProfileCustomerRequest;
use App\Domains\Customer\Http\Resources\CustomerComponentSelectResource;
use App\Domains\Customer\Http\Resources\CustomerResources;
use App\Domains\Customer\Model\Customer;
use App\Domains\Phone\Http\Actions\DeletePhoneAction;
use App\Domains\Phone\Http\Actions\RestorePhoneAction;
use App\Domains\Phone\Http\Actions\StorePhoneAction;
use App\Domains\Phone\Http\Actions\UpdatePhoneAction;
use App\Domains\User\Http\Actions\StoreUserAction;
use App\Domains\User\Http\Actions\UpdatePasswordUserAction;
use App\Domains\User\Http\Actions\UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Notifications\CreatePasswordNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Commands\Show;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CustomerRequest $request, CustomerAction $customerAction)
    {
        $data = $request->validated();
        $roles = $customerAction->execute($data, false);
        return response()->json($roles);
    }
    /**
     * Cliente com paginação
     * 
     * Todas os Clientes paginada
     *
     * @param CustomerRequest $request
     * @param \App\Domains\Customer\Http\Actions\CustomerAction $roleAction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withPagination(CustomerRequest $request, CustomerAction $customerAction)
    {
        $data = $request->validated();
        $roles = $customerAction->execute($data, true);
        return response()->json($roles);
    }
    /**
     * Cadastrar cliente
     * 
     * Cadastrar um novo cliente
     * @param StoreCustomerRequest $request
     * @param \App\Domains\Customer\Http\Actions\StoreCustomerAction $storeCustomerAction
     * @param \App\Domains\Phone\Http\Actions\StorePhoneAction $storePhoneAction
     * @param \App\Domains\User\Http\Actions\StoreUserAction $storeUserAction
     */
    public function store(
        StoreCustomerRequest $request,
        StoreCustomerAction $storeCustomerAction,
        StorePhoneAction $storePhoneAction,
        StoreAddressAction $storeAddressAction,
        StoreUserAction $storeUserAction
    ) {
        $data = $request->validated();

        DB::transaction(function () use ($data, $storeCustomerAction, $storePhoneAction, $storeAddressAction, $storeUserAction) {
            $customer = $storeCustomerAction->execute($data);
            $storeUserAction->execute($data, $customer);
            $storePhoneAction->execute($data['phones'], $customer);
            if (isset($data['address'])) {
                $storeAddressAction->execute($data['address'], $customer);
            }

            return $customer;
        });

        return response()->json(['message' => __('Customer created successfully.')], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowCustomerRequest $request, Customer $customer)
    {
        // $customer->relationLoadWithTrashed();
        $customer = new CustomerResources($customer);
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateCustomerRequest $request,
        Customer $customer,
        UpdateCustomerAction $updateCustomerAction,
        UpdatePhoneAction $updatePhoneAction,
        StorePhoneAction $storePhoneAction,
        DeletePhoneAction $deletePhoneAction,
        UpdateAddressAction $updateAddressAction,
        StoreAddressAction $storeAddressAction,
        UpdateUserAction $updateUserAction
    ) {
        $data = $request->validated();
        DB::transaction(function () use (
            $data,
            $customer,
            $updateCustomerAction,
            $updatePhoneAction,
            $storePhoneAction,
            $deletePhoneAction,
            $updateAddressAction,
            $storeAddressAction,
            $updateUserAction,
        ) {
            $updateCustomerAction->execute($data, $customer);
            $updateUserAction->execute($customer->user, $data);

            if (isset($data['address'])) {
                if (isset($data['address']['id'])) {
                    $updateAddressAction->execute($data['address']);
                } else {
                    $storeAddressAction->execute($data['address'], $customer);
                }
            }


            $phonesEdit = collect($data['phones'])->filter(function ($phone) {
                return isset($phone['id']);
            });
            $phonesCreate = collect($data['phones'])->filter(function ($phone) {
                return !isset($phone['id']);
            });

            $updatePhoneAction->execute($phonesEdit, $customer);
            $storePhoneAction->execute($phonesCreate->toArray(), $customer);

            if (isset($data['delete_phones'])) {
                $deletePhoneAction->execute($data['delete_phones']);
            }
        });
        return response()->json(['message' => __('Customer updated successfully.')], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        DeleteCustomerRequest $request,
        Customer $customer,
        DeleteCustomerAction $deleteCustomerAction,
        DeletePhoneAction $deletePhoneAction,
        DeleteAddressAction $deleteAddressAction
    ) {
        DB::transaction(function () use ($customer, $deleteCustomerAction, $deletePhoneAction, $deleteAddressAction) {
            $customer->relationLoadWithTrashed();
            $deleteCustomerAction->execute($customer);
            $deletePhoneAction->execute($customer->phones->pluck('id')->toArray());
            $deleteAddressAction->execute($customer->address);
        });
        return response()->json(['message' => __('Customer deleted successfully.')], 201);
    }
    public function restore(RestoreCustomerRequest $request, Customer $customer, RestoreCustomerAction $restoreCustomerAction, RestorePhoneAction $restorePhoneAction, RestoreAddressAction $restoreAddressAction)
    {
        DB::transaction(function () use ($customer, $restoreCustomerAction, $restorePhoneAction, $restoreAddressAction) {
            $customer->relationLoadWithTrashed();
            $restoreCustomerAction->execute($customer);
            $restorePhoneAction->execute($customer->phones);
            $restoreAddressAction->execute($customer->address);
        });
        return response()->json(['message' => __('Customer restored successfully.')], 201);
    }

    public function createPassword(CreatePasswordRequest $request)
    {
        $data = $request->validated();
        $status = Password::broker('create_password_customer')->reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password,
                    "email_verified_at" => now(),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __('Password created successfully.')])
            : response()->json(['message' => __($status, ['type' => "criação"])], 400);
    }
    public function resendCreatePassword(Customer $customer)
    {
        $status = Password::broker('create_password_customer')->sendResetLink(
            ['email' => $customer->email],
            function ($user, $token) {
                $url = config('app.url_front') . '/cliente/criar-senha';
                $user->notify(new CreatePasswordNotification($token, $url));
            }
        );
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status, ['type' => "criar"])])
            : response()->json(['message' => __($status, ['type' => "criação"])], 400);
    }

    public function updateAddress(UpdateAddressCustomerRequest $request, Customer $customer, UpdateAddressAction $updateAddressAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $customer, $updateAddressAction) {
            $data['id'] = $customer->address->id;
            $updateAddressAction->execute($data);
        });
        return response()->json(['message' => __('Address updated successfully.')], 201);
    }
    public function updatePhone(UpdatePhoneCustomerRequest $request, Customer $customer, UpdatePhoneAction $updatePhoneAction, StorePhoneAction $storePhoneAction, DeletePhoneAction $deletePhoneAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $customer, $updatePhoneAction, $storePhoneAction, $deletePhoneAction) {
            $phonesEdit = collect($data['phones'])->filter(function ($phone) {
                return isset($phone['id']);
            });
            $phonesCreate = collect($data['phones'])->filter(function ($phone) {
                return !isset($phone['id']);
            });

            $updatePhoneAction->execute($phonesEdit, $customer);
            $storePhoneAction->execute($phonesCreate->toArray(), $customer);

            if (isset($data['delete_phones'])) {
                $deletePhoneAction->execute($data['delete_phones']);
            }
        });
        return response()->json(['message' => __('Phones updated successfully.')], 201);
    }
    public function updateProfile(UpdateProfileCustomerRequest $request, Customer $customer, UpdateCustomerAction $updateCustomerAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $customer, $updateCustomerAction) {
            $updateCustomerAction->execute($data, $customer);
        });
        return response()->json(['message' => __('Perfil updated successfully.')], 201);
    }
    public function updatePassword(UpdatePasswordCustomerRequest $request, Customer $customer, UpdatePasswordUserAction $updatePasswordUserAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $customer, $updatePasswordUserAction) {

            if (isset($data['password_current']) && !Hash::check($data['password_current'], $customer->user->password)) {
                throw ValidationException::withMessages([
                    'password_current' => [__('The current password is different from the one provided')],
                ]);
            }

            $updatePasswordUserAction->execute($customer->user, $data);
        });
        return response()->json(['message' => __('Password updated successfully.')], 201);
    }

    public function componentSelect(ComponentSelectCustomerRequest $request, CustomerAction $customerAction)
    {
        $data = $request->validated();
        $customers = $customerAction->query($data, true);
        $customers = new CustomerComponentSelectResource($customers);
        return response()->json($customers);
    }
}
