<?php

namespace App\Domains\Auth\Http\Controller;

use App\Domains\Auth\Http\Actions\LoginAction;
use App\Domains\Auth\Http\Actions\LoginCustomerAction;
use App\Domains\Auth\Http\Requests\LoginCustomerRequest;
use App\Domains\Auth\Http\Requests\LoginRequest;
use App\Domains\Auth\Http\Requests\VerifyCodeRequest;
use App\Domains\CodeVerification\Model\CodeVerification;
use App\Domains\Customer\Http\Resources\CustomerResources;
use App\Domains\Customer\Model\Customer;
use App\Domains\TrustedDevice\Http\Actions\TrustedDeviceAction;
use App\Domains\User\Http\Resources\UserAuthResources;
use App\Domains\User\Model\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dedoc\Scramble\Attributes\HeaderParameter;
use Illuminate\Support\Facades\DB;
use Pest\ArchPresets\Custom;

class AuthController extends Controller
{
    /**
     * login
     * 
     * Efetua o login do usuário
     * 
     * @unauthenticated
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
     * login cliente
     * 
     * Efetua o login do usuário cliente
     * 
     * @unauthenticated
     * 
    
     */
    #[HeaderParameter('X-Device-Token', 'Mobile enviar o mac address | Web enviar token gerado pelo browser', type: 'string')]
    public function loginCustomer(LoginCustomerRequest $request, LoginCustomerAction $loginCustomerAction)
    {
        $data = $request->validated();
        $data += [
            'ip' => $request->ip(),
            'User-Agent' => $request->header('User-Agent'),
            'X-Device-Token' => $request->header('X-Device-Token'),
        ];
        return $loginCustomerAction->execute($data);
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
        if ($user instanceof Customer) {
            $user = new CustomerResources($user);
            return response()->json($user);
        }
        $user = new UserAuthResources($user);
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
    public function verifyCode(VerifyCodeRequest $request, LoginAction $loginAction, TrustedDeviceAction $trustedDeviceAction, LoginCustomerAction $loginCustomerAction)
    {

        $data = $request->validated();
        $email = $data['email'];
        $code = $data['code'];
        $deviceToken = $request->header('X-Device-Token');

        return DB::transaction(function () use ($email, $code, $deviceToken, $request, $loginAction, $trustedDeviceAction, $loginCustomerAction) {

            $verification = CodeVerification::where('email', $email)
                ->where('code', $code)
                ->where('expires_at', '>', now())
                ->first();

            if (!$verification) {
                return response()->json(['message' => __('Code invalid or expired')], 401);
            }


            if ($verification->guard == 'sanctum') {
                $user = User::where('email', $email)->firstOrFail();
            }
            if ($verification->guard == 'customer') {
                $user = Customer::where('email', $email)->firstOrFail();
            }


            $verification->delete();

            if ($deviceToken && $request) {
                $trustedDeviceAction->execute($user, $deviceToken, $request->ip(), $request->header('User-Agent'));
            }

            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            if ($verification->guard == 'sanctum') {
                return $loginAction->authenticate($user);
            }

            return $loginCustomerAction->authenticate($user);
        });
    }

    public function verifyMail($user_id, Request $request)
    {

        if (!$request->hasValidSignature()) {
            return redirect()->away(config('app.url_front') . '/verificado?status=failed');
        }

        $user = User::findOrFail($user_id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->away(config('app.url_front') . '/verificado?status=success');
    }
    public function mailResend($user_id)
    {
        $user = User::find($user_id);
        if ($user->hasVerifiedEmail()) {
            return response()->json("Email já verificado.", 200);
        }

        $user->sendEmailVerificationNotification();

        return response()->json("Link de verificação de e-mail enviado");
    }

    public function verifyMailCustomer($customer_id, Request $request)
    {

        if (!$request->hasValidSignature()) {
            return redirect()->away(config('app.url_front') . '/verificado?status=failed');
        }

        $user = Customer::findOrFail($customer_id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->away(config('app.url_front') . '/verificado?status=success');
    }
    public function mailResendCustomer($customer_id)
    {
        $user = Customer::findOrFail($customer_id);
        if ($user->hasVerifiedEmail()) {
            return response()->json("Email já verificado.", 200);
        }

        $user->sendEmailVerificationNotification();

        return response()->json("Link de verificação de e-mail enviado");
    }
}
