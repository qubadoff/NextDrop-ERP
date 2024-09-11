<?php

namespace App\Models;

use App\Employee\EmployeePenalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePenal extends Model
{
    use HasFactory;

    protected $table = 'employee_penals';

    protected $guarded  = ['id'];

    protected $casts = [
        'status' => EmployeePenalStatus::class
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
