<?php

namespace App\Domains\TrustedDevice\Http\Actions;

use App\Domains\TrustedDevice\Model\TrustedDevice;
use App\Domains\User\Model\User;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;

class TrustedDeviceAction
{
    public function execute($model, string $deviceToken, string $ip, string $userAgent)
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        $existing =  TrustedDevice::query()
            ->where('owner_id', $model->id)
            ->where('owner_type', $model->getMorphClass())
            ->get()
            ->first(function ($device) use ($deviceToken) {
                return Hash::check($deviceToken, $device->device_token_hash);
            });
        if (!$existing) {
            TrustedDevice::create([
                'owner_id' => $model->id,
                'owner_type' => $model->getMorphClass(),
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
