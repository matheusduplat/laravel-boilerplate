<?php

namespace App\Domains\RequestManagement\Model;

use App\Domains\RequestManagement\Enums\StatusProcedureRequestManagement;
use App\Domains\RequestManagement\Enums\StatusRequestManagement;
use App\Domains\RequestManagement\Enums\TypeRequestManagement;
use App\Domains\RequestManagement\Policy\RequestManagementPolicy;
use App\Domains\RequestManagement\Traits\RequestManagementMethod;
use App\Domains\RequestManagement\Traits\RequestManagementRelationship;
use App\Domains\RequestManagement\Traits\RequestManagementScope;
use App\Trait\GlobalSoftDelete;
use Database\Factories\RequestManagementFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

#[UsePolicy(RequestManagementPolicy::class)]
class RequestManagement extends Model implements Auditable
{
    use AuditableTrait, GlobalSoftDelete, HasFactory;
    use RequestManagementRelationship, RequestManagementScope, RequestManagementMethod;
    protected $table = 'request_management';
    protected $fillable = [
        "code",
        'type',
        'note_customer',
        'location_performing_procedure',
        'attachment',
        'code_guide',
        'status',
        'status_procedure',
        'customer_id',
        'date_close',
        'customer_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    protected $auditExclude  = [
        'code',
    ];

    protected $casts = [
        'date_close' => 'datetime',
        "status" => StatusRequestManagement::class,
        "status_procedure" => StatusProcedureRequestManagement::class,
        "type" => TypeRequestManagement::class
    ];

    protected static function newFactory()
    {
        return RequestManagementFactory::new();
    }
}
