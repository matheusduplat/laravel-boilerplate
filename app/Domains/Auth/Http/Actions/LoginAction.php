<?php

namespace App\Domains\Auth\Http\Actions;

use App\Domains\CodeVerification\Model\CodeVerification;
use App\Domains\TrustedDevice\Http\Actions\TrustedDeviceAction;
use App\Domains\TrustedDevice\Model\TrustedDevice;
use App\Domains\User\Model\User;
use App\Notifications\SendCodeVerificationNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class LoginAction
{
    public function execute(array $data)
    {

        $trustedDeviceAction = new TrustedDeviceAction();
        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return response()->json(['message' => 'Invalid email'], 403);
        }
        if (! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid password'], 403);
        }

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email not verified'], 403);
        }



        $deviceToken = $data['X-Device-Token'];
        $userAgent = $data['User-Agent'];
        $ip = $data['ip'];

        if (!$user->secure_login_email) {

            if ($deviceToken  && $ip && $userAgent) {
                $trustedDeviceAction->execute($user, $deviceToken, $ip, $userAgent);
            }

            return $this->authenticate($user);
        }


        if ($deviceToken) {
            $trusted = TrustedDevice::where('user_id', $user->id)
                ->where('expires_at', '>', now())
                ->get()
                ->first(function ($device) use ($deviceToken) {
                    return Hash::check($deviceToken, $device->device_token_hash);
                });

            if ($trusted) {
                return $this->authenticate($user);
            }
        }

        $this->sendVerificationCode($user);
        return response()->json(['message' => 'Code sent to email'], 200);
    }

    public function authenticate(User $user)
    {
        if (!config('app.access_simultaneous')) {
            $user->tokens()->delete();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }
    public function sendVerificationCode(User $user)
    {
        do {
            $code = mt_rand(100000, 999999);
        } while (CodeVerification::where('code', $code)
            ->where('expires_at', '>', now())
            ->exists()
        );

        CodeVerification::updateOrCreate(
            ['email' => $user->email],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        Notification::send($user, new SendCodeVerificationNotification($code));

        return $code;
    }
}
