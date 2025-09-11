<?php

namespace App\Domains\IncomeReport\Model;

use App\Domains\IncomeReport\Policy\IncomeReportPolicy;
use App\Domains\IncomeReport\Traits\IncomeReportMethod;
use App\Domains\IncomeReport\Traits\IncomeReportRelationship;
use App\Domains\IncomeReport\Traits\IncomeReportScope;
use App\Trait\GlobalSoftDelete;
use Database\Factories\IncomeReportFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

#[UsePolicy(IncomeReportPolicy::class)]
class IncomeReport extends Model implements Auditable
{
    use AuditableTrait, GlobalSoftDelete, HasFactory;
    use IncomeReportRelationship, IncomeReportMethod, IncomeReportScope;
    protected $table = 'income_reports';

    protected $auditExclude  = [
        'attachment',
    ];

    protected $fillable = [
        'customer_id',
        'year',
        "notified",
        'attachment',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    protected $casts = [
        'notified' => 'boolean',
    ];

    protected static function newFactory()
    {
        return IncomeReportFactory::new();
    }
}
