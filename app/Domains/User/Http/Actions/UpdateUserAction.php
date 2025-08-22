<?php

namespace App\Domains\User\Http\Actions;

use App\Domains\User\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UpdateUserAction
{
    public function execute(User $user, array $data)
    {

        $data['updated_by'] = Auth::user()->name ?? null;
        $data['name'] = $this->nameAndSurname($data['name']);

        $user->update($data);

        if (isset($data['role'])) {
            $user->syncRolesWithAudit([$data['role']]);
        }
        if (isset($data['permissions'])) {
            $user->syncPermissionsWithAudit($data['permissions']);
        }


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
