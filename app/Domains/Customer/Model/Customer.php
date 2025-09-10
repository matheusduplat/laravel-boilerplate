<?php

namespace App\Domains\Customer\Model;

use App\Domains\Customer\Enums\CustomerStatus;
use App\Domains\Customer\Policy\CustomerPolicy;
use App\Domains\Customer\Traits\CustomerRelationship;
use App\Domains\Customer\Traits\CustomerMethod;
use App\Domains\Customer\Traits\CustomerScope;
use App\Trait\GlobalSoftDelete;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;


#[UsePolicy(CustomerPolicy::class)]
class Customer extends Authenticatable implements Auditable
{
    use AuditableTrait;
    use GlobalSoftDelete,  CustomerRelationship, CustomerMethod, CustomerScope, HasFactory;
    protected $table = 'customers';
    protected $fillable = [
        'name',
        'name_social',
        'cpf',
        'birth_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $casts = [
        'birth_date' => 'date',
        'status' => CustomerStatus::class,
    ];
    protected static function newFactory()
    {
        return CustomerFactory::new();
    }
}
