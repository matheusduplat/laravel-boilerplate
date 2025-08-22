<?php

namespace App\Domains\Role\Model;

use App\Domains\Role\Policy\RolePolicy;
use App\Domains\Role\Traits\RoleMethod;
use App\Domains\Role\Traits\RoleScope;
use App\Trait\GlobalRelationship;
use App\Trait\GlobalSoftDelete;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role as SpatieRole;
use \OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

#[UsePolicy(RolePolicy::class)]

class Role extends SpatieRole implements Auditable
{
    use GlobalRelationship, RoleMethod, AuditableTrait, GlobalSoftDelete, HasFactory, RoleScope;
    protected $guard_name = 'sanctum';
    protected $fillable = [
        'name',
        'guard_name',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    protected static function newFactory()
    {
        return RoleFactory::new();
    }
}
