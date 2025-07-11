<?php

namespace App\Domains\User\Http\Actions;

use App\Domains\User\Http\Resources\UserResource;
use App\Domains\User\Model\User;

class UsersAction
{
    public function index(array $data, bool $is_paginate)
    {
        $users = User::query()
            ->when(isset($data['name']), function ($query) use ($data) {
                $query->where('name', 'like', "%{$data['name']}%");
            })
            ->when(isset($data['email']), function ($query) use ($data) {
                $query->where('email', 'like', "%{$data['email']}%");
            });

        $users = $is_paginate ? $users->paginate(10) : $users->get();
        return UserResource::collection($users);
    }

    public function execute(array $data, bool $is_paginate)
    {
        return $this->index($data, $is_paginate);
    }
}
