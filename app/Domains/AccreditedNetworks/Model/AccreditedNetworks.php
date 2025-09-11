<?php

namespace App\Domains\AccreditedNetworks\Model;

use App\Domains\AccreditedNetworks\Enums\StatusAccreditedNetworks;
use App\Domains\AccreditedNetworks\Enums\TypeServiceAccreditedNetworks;
use App\Domains\AccreditedNetworks\Policy\AccreditedNetworksPolicy;
use App\Domains\AccreditedNetWorks\Traits\AccreditedNetWorksMethod;
use App\Domains\AccreditedNetworks\Traits\AccreditedNetWorksRelationship;
use App\Domains\AccreditedNetWorks\Traits\AccreditedNetWorksScope;
use App\Trait\GlobalSoftDelete;
use Database\Factories\AccreditedNetWorksFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

#[UsePolicy(AccreditedNetworksPolicy::class)]
class AccreditedNetworks extends Model implements Auditable
{
    use AuditableTrait, GlobalSoftDelete, HasFactory;
    use AccreditedNetWorksRelationship, AccreditedNetWorksScope, AccreditedNetWorksMethod;
    protected $table = 'accredited_networks';
    protected $fillable = [
        'name',
        'type_service',
        'specialties_served',
        'status',
        'exams_attended',
        'plans_attended',
        'urgency_emergency',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'type_service' => TypeServiceAccreditedNetworks::class,
        'specialties_served' => 'array',
        'exams_attended' => 'array',
        'plans_attended' => 'array',
        'urgency_emergency' => 'boolean',
        "status" => StatusAccreditedNetworks::class
    ];

    protected static function newFactory()
    {
        return AccreditedNetWorksFactory::new();
    }
}
