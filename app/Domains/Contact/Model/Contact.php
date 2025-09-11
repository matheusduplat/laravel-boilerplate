<?php

namespace App\Domains\Contact\Model;

use App\Domains\Contact\Enums\StatusContact;
use App\Domains\Contact\Policy\ContactPolicy;
use App\Domains\Contact\Traits\ContactMethod;
use App\Domains\Contact\Traits\ContactRelationship;
use App\Domains\Contact\Traits\ContactScope;
use App\Trait\GlobalSoftDelete;
use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

#[UsePolicy(ContactPolicy::class)]
class Contact extends Model implements Auditable
{
    use AuditableTrait, HasFactory, GlobalSoftDelete;
    use ContactRelationship, ContactMethod, ContactScope;

    protected $table = 'contacts';
    protected $fillable = [
        'uuid',
        "message",
        "name",
        "phone",
        "email",
        "status",
        "customer_id",
        "created_by",
        "updated_by",
        "deleted_by"
    ];

    protected $casts = [
        "status" => StatusContact::class,
    ];

    public static function newFactory()
    {
        return ContactFactory::new();
    }
}
