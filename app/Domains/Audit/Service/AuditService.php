<?php

namespace App\Domains\Audit\Service;

use OwenIt\Auditing\Models\Audit;

class AuditService
{
    public static function auditManyToMany($model, string $event, array $old, array $new, string $key = 'related_ids')
    {
        if ($old != $new) {
            Audit::create([
                'auditable_type' => $model->getMorphClass(),
                'auditable_id' => $model->id,
                'user_type' => auth()->check() ? auth()->user()->getMorphClass() : null,
                'user_id' => auth()->id() ?? null,
                'event' => $event,
                'old_values' => [$key => $old],
                'new_values' => [$key => $new],
                'url' => request()->fullUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'tags' => 'manual',
            ]);
        }
    }
}
