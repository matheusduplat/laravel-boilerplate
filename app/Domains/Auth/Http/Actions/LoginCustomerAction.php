<?php

namespace App\Domains\Auth\Http\Actions;

use App\Domains\CodeVerification\Model\CodeVerification;
use App\Domains\Customer\Http\Resources\CustomerResources;
use App\Domains\Customer\Model\Customer;
use App\Domains\TrustedDevice\Http\Actions\TrustedDeviceAction;
use App\Domains\TrustedDevice\Model\TrustedDevice;
use App\Domains\User\Http\Resources\UserAuthResources;
use App\Domains\User\Model\User;
use App\Notifications\SendCodeVerificationNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class LoginCustomerAction
{
    public function execute(array $data)
    {
        $customer = Customer::where('cpf', $data['cpf'])->first();
        if (!$customer) {
            return response()->json(['message' => __('Customer not found')], 403);
        }

        if (! Hash::check($data['password'], $customer->password)) {
            return response()->json(['message' => __('Invalid password')], 403);
        }

        if (!$customer->hasVerifiedEmail()) {
            return response()->json(['message' => __('Email not verified'), 'user' => $customer], 403);
        }


        $trustedDeviceAction = new TrustedDeviceAction();

        $deviceToken = $data['X-Device-Token'];
        $userAgent = $data['User-Agent'];
        $ip = $data['ip'];

        if (!$customer->secure_login_email) {

            if ($deviceToken  && $ip && $userAgent) {
                $trustedDeviceAction->execute($customer, $deviceToken, $ip, $userAgent);
            }
            return $this->authenticate($customer);
        }


        if ($deviceToken) {
            $trusted =
                TrustedDevice::query()
                ->where('owner_id', $customer->id)
                ->where('owner_type', $customer->getMorphClass())
                ->where('expires_at', '>', now())
                ->get()
                ->first(function ($device) use ($deviceToken) {
                    return Hash::check($deviceToken, $device->device_token_hash);
                });

            if ($trusted) {
                return $this->authenticate($customer);
            }
        }

        $this->sendVerificationCode($customer);
        return response()->json(['message' => __('Code sent to email')], 200);
    }

    public function authenticate(Customer $user)
    {
        if (!config('app.access_simultaneous')) {
            $user->tokens()->delete();
        }

        $token = $user->createToken('auth_token_customer')->plainTextToken;

        return response()->json([
            "message" => __("success login"),
            'token' => $token,
            'user' => new CustomerResources($user)
        ]);
    }
    public function sendVerificationCode(Customer $customer)
    {
        do {
            $code = mt_rand(100000, 999999);
        } while (CodeVerification::where('code', $code)
            ->where('expires_at', '>', now())
            ->exists()
        );

        CodeVerification::updateOrCreate(
            ['email' => $customer->email],
            [
                'code' => $code,
                'guard' => 'customer',
                'expires_at' => now()->addMinutes(10),
            ]
        );

        Notification::send($customer, new SendCodeVerificationNotification($code));

        return $code;
    }
}
