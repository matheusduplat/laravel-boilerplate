<?php

namespace App\Domains\User\Http\Controller;

use App\Domains\User\Http\Actions\UsersAction;
use App\Domains\User\Http\Requests\UserRequest;
use App\Domains\User\Model\User;
use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
        $user = User::create($request->all());
        $user->assignRoles(['1']);
        return response()->json($user);
    }

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
}
