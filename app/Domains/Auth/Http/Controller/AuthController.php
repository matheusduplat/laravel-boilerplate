<?php

namespace App\Domains\Auth\Http\Controller;

use App\Domains\Auth\Http\Requests\LoginRequest;
use App\Domains\User\Model\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return response()->json('Email invalido', 404);
        }
        if (! Hash::check($data['password'], $user->password)) {
            return response()->json('Senha invalida', 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json('Deslogado com sucesso');
    }

    public function me()
    {
        $user = Auth::user();
        return response()->json([
            'user' => $user
        ]);
    }
}
