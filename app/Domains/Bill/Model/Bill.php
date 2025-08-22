<?php

namespace App\Domains\Bill\Model;

use App\Domains\Bill\Enums\BillStatus;
use App\Domains\Bill\Policy\BillPolicy;
use App\Domains\Bill\Traits\BillMethod;
use App\Domains\Bill\Traits\BillRelationship;
use App\Domains\Bill\Traits\BillScope;
use App\Trait\GlobalSoftDelete;
use Database\Factories\BillFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

#[UsePolicy(BillPolicy::class)]
class Bill extends Model implements Auditable
{
    use AuditableTrait, GlobalSoftDelete, HasFactory;
    use BillRelationship, BillScope, BillMethod;

    protected $auditExclude  = [
        'base64',
    ];
    protected $table = 'bills';

    protected $fillable = [
        'base64',
        'title_id',
        'base_year',
        'base_month',
        'issue_date',
        'due_date',
        'low_date',
        'value',
        'status',
        'customer_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'status' => BillStatus::class,
        'issue_date' => 'date:Y-m-d',
        'due_date' => 'date:Y-m-d',
        'low_date' => 'date:Y-m-d',
    ];
    protected static function newFactory()
    {
        return BillFactory::new();
    }
}
