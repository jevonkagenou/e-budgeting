<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Budget extends Model
{
    use HasFactory, SoftDeletes, HasUuids, LogsActivity;

    protected $fillable = [
        'fiscal_year_id',
        'division_id',
        'budget_category_id',
        'name',
        'total_amount',
        'used_amount',
        'start_date',
        'end_date',
        'created_by',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Data anggaran telah di{$eventName}");
    }

    public function division()
    {
        return $this->belongsTo(Division::class)->withTrashed();
    }

    public function budgetCategory()
    {
        return $this->belongsTo(BudgetCategory::class)->withTrashed();
    }

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class)->withTrashed();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
}
