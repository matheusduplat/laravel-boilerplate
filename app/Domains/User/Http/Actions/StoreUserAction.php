<?php

namespace App\Domains\User\Http\Actions;

use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\Role\Model\Role;
use App\Domains\User\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StoreUserAction
{
    public function execute(array $data)
    {
        $data['created_by'] = Auth::user()->name ?? null;
        $data['name'] = $this->nameAndSurname($data['name']);

        $user = User::create([
            ...$data,
            'password' => $data['password'] ?? 'Password@321'
        ]);

        $user->attachRolesWithAudit([$data['role']]);

        $user->attachPermissionsWithAudit($data['permissions'] ?? []);


        if (isset($data['password'])) {
            $user->sendEmailVerificationNotification();
            return $user;
        }

        $user->sendCreatePasswordNotification();

        return $user;
    }

    public function nameAndSurname(string $nome): string
    {
        $ignoradas = collect(['da', 'de', 'do', 'das', 'dos']);

        return collect(Str::of($nome)->squish()->explode(' '))
            ->reject(fn($parte) => $ignoradas->contains(mb_strtolower($parte)))
            ->map(fn($parte) => Str::ucfirst(Str::lower($parte)))
            ->take(2)
            ->implode(' ');
    }
}
