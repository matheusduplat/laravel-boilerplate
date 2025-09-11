<?php

namespace App\Domains\RequestManagementResponse\Model;

use App\Domains\RequestManagementResponse\Traits\RequestManagementResponseMethod;
use App\Domains\RequestManagementResponse\Traits\RequestManagementResponseRelationship;
use App\Domains\RequestManagementResponse\Traits\RequestManagementResponseScope;
use App\Trait\GlobalSoftDelete;
use Database\Factories\RequestManagementResponseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

class RequestManagementResponse extends Model implements Auditable
{
    use AuditableTrait, GlobalSoftDelete, HasFactory;
    use RequestManagementResponseRelationship, RequestManagementResponseScope, RequestManagementResponseMethod;
    protected $table = 'request_management_responses';
    protected $fillable = [
        'hidden',
        'authorable_id',
        'authorable_type',
        'answer',
        'request_management_id',
        'date_hour',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'date_hour' => 'datetime',
        'hidden' => 'boolean'
    ];

    protected static function newFactory()
    {
        return RequestManagementResponseFactory::new();
    }
}
