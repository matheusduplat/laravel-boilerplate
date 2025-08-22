<?php

namespace App\Domains\Employee\Model;

use App\Domains\Employee\Enums\EmployeeStatus;
use App\Domains\Employee\Policy\EmployeePolicy;
use App\Domains\Employee\Traits\EmployeeMethod;
use App\Domains\Employee\Traits\EmployeeRelationship;
use App\Domains\Employee\Traits\EmployeeScope;
use App\Trait\GlobalRelationship;
use App\Trait\GlobalSoftDelete;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

#[UsePolicy(EmployeePolicy::class)]
class Employee extends Model implements Auditable
{
    use AuditableTrait;
    use GlobalRelationship, EmployeeRelationship, GlobalSoftDelete, HasFactory, EmployeeScope, EmployeeMethod;
    protected $table = 'employees';
    protected $fillable = [
        'name',
        'status',
        'user_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'status' => EmployeeStatus::class
    ];
    protected static function newFactory()
    {
        return EmployeeFactory::new();
    }
}
