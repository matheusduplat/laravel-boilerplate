<?php

namespace App\Domains\Auth\Http;

use App\Domains\Auth\Request\ForgotPasswordRequest;
use App\Domains\Auth\Request\ResetPasswordRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email ou senha invalido'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Deslogado com sucesso']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = auth()->refresh();
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Tempo de atualização de token expirado'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'user' => auth()->user()
        ]);
    }
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $credentials = $request->validated();
        $status = Password::sendResetLink($credentials);

        return $status === Password::RESET_LINK_SENT
            ? response()->json("Email de redefinição de senha enviado.") : response()->json(["error" => "Problema ao enviar solicitação, tente novamente mais tarde!"], 500);
    }
    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password
                ]);
                $user->save();
            }
        );

        return $status == Password::PASSWORD_RESET ?
            response()->json("Senha Redefinida com Sucesso.") :
            response()->json("Error ao Redefinir Senha. Token invalido ou expirado.", 500);
    }
}
