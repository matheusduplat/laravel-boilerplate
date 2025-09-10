<?php

namespace App\Domains\Auth\Http\Controller;

use App\Domains\Auth\Http\Requests\ResetPasswordRequest;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    public function reset(ResetPasswordRequest $request)
    {
        $data = $request->validated();

        $status = Password::reset(
            $data,
            function ($user) use ($data) {
                $user->forceFill([
                    'password' => $data['password'],
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)]);
        }

        return response()->json(['message' => __($status, ['type' => __("redefinition")])], 400);
    }
    public function resetCustomer(ResetPasswordRequest $request)
    {
        $data = $request->validated();

        $status = Password::broker('customer')->reset(
            $data,
            function ($user) use ($data) {
                $user->forceFill([
                    'password' => $data['password'],
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)]);
        }

        return response()->json(['message' => __($status, ['type' => __("redefinition")])], 400);
    }
}
