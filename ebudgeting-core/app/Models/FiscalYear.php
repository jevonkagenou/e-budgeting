<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FiscalYear extends Model
{
    use HasFactory, HasUuids, SoftDeletes, LogsActivity;

    protected $fillable = [
        'year',
        'is_active',
        'start_date',
        'end_date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Data pengguna telah di{$eventName}");
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
}
