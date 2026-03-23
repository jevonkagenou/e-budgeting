<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FiscalYear extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'year',
        'is_active',
        'start_date',
        'end_date',
    ];

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
}
