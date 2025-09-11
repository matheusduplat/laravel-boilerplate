<?php

namespace App\Domains\Copaticipation\Model;

use App\Domains\Copaticipation\Policy\CopaticipationPolicy;
use App\Domains\Copaticipation\Traits\CopaticipationMethod;
use App\Domains\Copaticipation\Traits\CopaticipationRelationship;
use App\Domains\Copaticipation\Traits\CopaticipationScope;
use App\Trait\GlobalSoftDelete;
use Database\Factories\CopaticipationFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

#[UsePolicy(CopaticipationPolicy::class)]
class Copaticipation extends Model implements Auditable
{
    use AuditableTrait, HasFactory, GlobalSoftDelete;
    use CopaticipationScope, CopaticipationRelationship, CopaticipationMethod;
    protected $table = 'copaticipations';
    protected $auditExclude  = [
        'base64',
    ];
    protected $fillable = [
        'base_month',
        'base_year',
        'base64',
        'customer_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function newFactory()
    {
        return CopaticipationFactory::new();
    }
}
