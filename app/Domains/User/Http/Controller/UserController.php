<?php

namespace App\Domains\User\Http\Controller;

use App\Domains\User\Http\Actions\UsersAction;
use App\Domains\User\Http\Requests\CreatePasswordRequest;
use App\Domains\User\Http\Requests\UserRequest;
use App\Domains\User\Model\User;
use App\Http\Controllers\Controller;
use App\Notifications\CreatePasswordNotification;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    /**
     * get all users
     * 
     * 
     */
    public function index(UserRequest $request,  UsersAction $usersAction)
    {
        $users = $usersAction->execute($request->validated(), false);
        return response()->json($users);
    }

    public function withPagination(UserRequest $request,  UsersAction $usersAction)
    {
        $users = $usersAction->execute($request->validated(), true);
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function createPassword(CreatePasswordRequest $request)
    {
        $data = $request->validated();
        $status = Password::broker('create_password')->reset(
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
            : response()->json(['message' => __($status, ['type' => __("creation")])], 400);
    }
    public function resendCreatePassword(User $user)
    {
        $status = Password::broker('create_password')->sendResetLink(
            ['email' => $user->email],
            function ($user, $token) {
                $url = config('app.url_front') . '/criar-senha';
                $user->notify(new CreatePasswordNotification($token, $url));
            }
        );
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status, ['type' => __("create")])])
            : response()->json(['message' => __($status, ['type' => __("creation")])], 400);
    }
}
