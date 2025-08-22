<?php

namespace App\Domains\User\Model;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Domains\User\Traits\UserMethod;
use App\Domains\User\Traits\UserRelationship;
use App\Trait\GlobalRelationship;
use App\Trait\GlobalSoftDelete;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;
use \OwenIt\Auditing\Auditable as AuditableTrait;

class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, UserRelationship, AuditableTrait, UserMethod;
    use GlobalSoftDelete, GlobalRelationship;


    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'secure_login_email',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'secure_login_email' => 'boolean'
        ];
    }
    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
