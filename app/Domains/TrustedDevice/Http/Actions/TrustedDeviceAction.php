<?php

namespace App\Domains\TrustedDevice\Http\Actions;

use App\Domains\User\Model\User;
use Illuminate\Http\Request;

class TrustedDeviceAction
{
    public function execute(User $user, string $deviceToken, string $ip, string $userAgent) {}
}
