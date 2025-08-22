<?php

namespace App\Domains\Permission\Model;

use App\Domains\Permission\Policy\PermissionPolicy;
use App\Trait\GlobalSoftDelete;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission as SpatiePermission;
use \OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

#[UsePolicy(PermissionPolicy::class)]
class Permission extends SpatiePermission implements Auditable
{
    use GlobalSoftDelete, AuditableTrait;
    protected $fillable = [
        'name',
        'guard_name',
        'description',
        'action',
        'subject',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
