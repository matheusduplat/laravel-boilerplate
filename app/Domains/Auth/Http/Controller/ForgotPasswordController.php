<?php

namespace App\Domains\Auth\Http\Controller;

use App\Domains\Auth\Http\Requests\ForgotPasswordRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $data = $request->validated();

        $status = Password::sendResetLink($data);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status, ['type' => __("reset")])]);
        }
        return response()->json(['message' => __($status, ['type' => __("redefinition")])], 400);
    }
    public function sendResetLinkEmailCustomer(ForgotPasswordRequest $request)
    {
        $data = $request->validated();

        $status = Password::broker('customer')->sendResetLink($data);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status, ['type' => __("reset")])]);
        }
        return response()->json(['message' => __($status, ['type' => __("redefinition")])], 400);
    }
}
