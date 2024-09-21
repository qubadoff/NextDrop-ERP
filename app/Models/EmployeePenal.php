<?php

namespace App\Models;

use App\Employee\EmployeePenalStatus;
use App\Employee\EmployeePenalTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePenal extends Model
{
    use HasFactory;

    protected $table = 'employee_penals';

    protected $guarded  = ['id'];

    protected $casts = [
        'status' => EmployeePenalStatus::class,
        'penal_type' => EmployeePenalTypeEnum::class,

    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
