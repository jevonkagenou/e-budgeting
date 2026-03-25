<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Storage;

class Reimbursement extends Model
{
    use HasFactory, SoftDeletes, HasUuids, LogsActivity;

    protected $fillable = [
        'user_id',
        'budget_id',
        'title',
        'description',
        'amount',
        'receipt_path',
        'status',
        'action_by',
        'rejection_reason',
    ];

    protected static function booted()
    {
        static::forceDeleted(function ($reimbursement) {
            if ($reimbursement->receipt_path) {
                Storage::disk('public')->delete($reimbursement->receipt_path);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Data pengajuan dana telah di{$eventName}");
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id')->withTrashed();
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by')->withTrashed();
    }
}
