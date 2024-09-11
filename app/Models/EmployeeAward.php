<?php

namespace App\Models;

use App\Employee\EmployeeAwardStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAward extends Model
{
    use HasFactory;

    protected $table = 'employee_awards';

    protected $guarded = [];

    protected $casts = [
        'status' => EmployeeAwardStatus::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
