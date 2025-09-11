<?php

namespace App\Domains\Customer\Model;

use App\Domains\Customer\Enums\CustomerStatus;
use App\Domains\Customer\Policy\CustomerPolicy;
use App\Domains\Customer\Traits\CustomerRelationship;
use App\Domains\Customer\Traits\CustomerMethod;
use App\Domains\Customer\Traits\CustomerScope;
use App\Trait\GlobalRelationship;
use App\Trait\GlobalSoftDelete;
use Database\Factories\CustomerFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[UsePolicy(CustomerPolicy::class)]
class Customer extends Authenticatable implements Auditable, MustVerifyEmail
{
    use AuditableTrait, Notifiable;
    use GlobalSoftDelete,  CustomerRelationship, CustomerMethod, HasRoles, HasApiTokens, CustomerScope, HasFactory;
    protected $guard = 'customer';
    protected $table = 'customers';
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'name_social',
        'cpf',
        'birth_date',
        'holder_id',
        'email_verified_at',
        'first_access',
        'secure_login_email',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $hidden = ['password', 'remember_token', 'email_verified_at'];
    protected $casts = [
        'first_access' => 'boolean',
        'password' => 'hashed',
        'birth_date' => 'date',
        'status' => CustomerStatus::class,
        'secure_login_email' => 'boolean',
        'email_verified_at' => 'datetime',
    ];
    protected static function newFactory()
    {
        return CustomerFactory::new();
    }
    public function receivesBroadcastNotificationsOn(): string
    {
        return "customer.{$this->id}";
    }
}
