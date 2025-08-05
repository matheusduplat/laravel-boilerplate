<?php

namespace App\Domains\TrustedDevice\Http\Actions;

use App\Domains\TrustedDevice\Model\TrustedDevice;
use App\Domains\User\Model\User;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;

class TrustedDeviceAction
{
    public function execute(User $user, string $deviceToken, string $ip, string $userAgent)
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        $existing =  TrustedDevice::where('user_id', $user->id)
            ->get()
            ->first(function ($device) use ($deviceToken) {
                return Hash::check($deviceToken, $device->device_token_hash);
            });
        if (!$existing) {
            TrustedDevice::create([
                'user_id' => $user->id,
                'device_token_hash' => Hash::make($deviceToken),
                'device' => $agent->device() ?? "Desconhecido",
                'platform' => $agent->platform() ?? "Desconhecido",
                'browser' => $agent->browser() ?? "Desconhecido",
                'user_agent' => $userAgent,
                'ip_address' => $ip,
                'expires_at' => now()->addDays(30),
            ]);
        }
    }
}
