<?php

namespace App\Domains\Auth\Http\Controller;

use App\Domains\Auth\Http\Actions\LoginAction;
use App\Domains\Auth\Http\Requests\LoginRequest;
use App\Domains\Auth\Http\Requests\VerifyCodeRequest;
use App\Domains\CodeVerification\Model\CodeVerification;
use App\Domains\TrustedDevice\Http\Actions\TrustedDeviceAction;
use App\Domains\User\Model\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dedoc\Scramble\Attributes\HeaderParameter;

class AuthController extends Controller
{
    /**
     * login
     * 
     * Efetua o login do usuário
     * 
     * @unauthenticated
     * 
     * 
     */
    #[HeaderParameter('X-Device-Token', 'Mobile enviar o mac address | Web enviar token gerado pelo browser', type: 'string')]
    public function login(LoginRequest $request, LoginAction $loginAction)
    {
        $data = $request->validated();
        $data += [
            'ip' => $request->ip(),
            'User-Agent' => $request->header('User-Agent'),
            'X-Device-Token' => $request->header('X-Device-Token'),
        ];
        return $loginAction->execute($data);
    }

    /**
     *
     * logout
     *
     * Deslogar o usuário
     *
     * 
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'logged out']);
    }


    /**
     *
     * me
     *
     * Retorna o usuário autenticado
     *
     * 
     */
    public function me()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    /**
     * 
     * verifyCode
     * 
     * Valida o código de verificação do usuário | somente se o usuário estiver com a flag secure_login_email true
     * 
     * @unauthenticated
     */
    #[HeaderParameter('X-Device-Token', 'Mobile enviar o mac address | Web enviar token gerado pelo browser', type: 'string')]
    public function verifyCode(VerifyCodeRequest $request, LoginAction $loginAction, TrustedDeviceAction $trustedDeviceAction)
    {

        $data = $request->validated();
        $email = $data['email'];
        $code = $data['code'];
        $deviceToken = $request->header('X-Device-Token');

        $verification = CodeVerification::where('email', $email)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return response()->json(['message' => 'Code invalid or expired'], 401);
        }

        $user = User::where('email', $email)->firstOrFail();
        $verification->delete();

        if ($deviceToken && $request) {
            $trustedDeviceAction->execute($user, $deviceToken, $request->ip(), $request->header('User-Agent'));
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return $loginAction->authenticate($user);
    }
}
