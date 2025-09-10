<?php

namespace App\Domains\Audit\Service;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use OwenIt\Auditing\Models\Audit;

class AuditService
{
    public static function auditManyToMany($model, string $event, array $old, array $new, string $key = 'related_ids')
    {
        if ($old != $new) {
            Audit::create([
                'auditable_type' => $model->getMorphClass(),
                'auditable_id' => $model->id,
                'owner_type' => auth()->check() ? auth()->user()->getMorphClass() : null,
                'owner_id' => auth()->id() ?? null,
                'event' => $event,
                'old_values' => [$key => $old],
                'new_values' => [$key => $new],
                'url' => self::resolveUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'tags' => 'manual',
            ]);
        }
    }

    public static function resolveUrl(): string
    {

        if (App::runningInConsole()) {
            $command = Request::server('argv', null);
            if (is_array($command)) {
                return implode(' ', $command);
            }

            return 'console';
        }

        return Request::fullUrl();
    }
}
