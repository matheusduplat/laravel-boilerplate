<?php

namespace App\Domains\RequestManagement\Traits;

use App\Domains\Audit\Service\AuditService;

trait RequestManagementMethod
{
    public function relationLoadWithTrashed(): void
    {
        $this->loadMissing([
            'responses' => function ($query) {
                $query->withTrashed();
            },
            'customer' => function ($query) {
                $query->withTrashed();
            },
        ]);
    }
    public function attachResponsibleWithAudit(array $responsible): void
    {
        if (empty($responsible)) return;

        $this->responsible()->attach($responsible);

        AuditService::auditManyToMany($this, 'responsible_attach', [], $responsible, 'responsible_id');
    }
    public function syncResponsibleWithAudit(array $responsible): void
    {
        $oldResponsible = $this->responsible()->get()->pluck('id')->toArray();

        $this->responsible()->sync($responsible);

        if ($oldResponsible !== $responsible) {
            AuditService::auditManyToMany($this, 'responsible_sync', $oldResponsible, $responsible, 'responsible_id');
        }
    }
}
