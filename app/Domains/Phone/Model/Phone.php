<?php

namespace App\Domains\Phone\Model;

use App\Domains\Phone\Enums\PhoneType;
use App\Domains\Phone\Traits\PhoneRelationship;
use App\Trait\GlobalRelationship;
use App\Trait\GlobalSoftDelete;
use Database\Factories\PhoneFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

class Phone extends Model implements Auditable
{
    use AuditableTrait;
    use PhoneRelationship, GlobalRelationship, GlobalSoftDelete, HasFactory;

    protected $table = 'phones';
    protected $fillable = [
        'number',
        'type',
        'whatsapp',
        'phoneable_id',
        'phoneable_type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'type' => PhoneType::class,
        'whatsapp' => 'boolean',
    ];

    protected static function newFactory()
    {
        return PhoneFactory::new();
    }
}
