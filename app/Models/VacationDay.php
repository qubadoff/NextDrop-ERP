<?php

namespace App\Models;

use App\Employee\EmployeeStatusEnum;
use App\Vacation\VacationPayTypeEnum;
use App\Vacation\VacationStatusEnum;
use App\Vacation\VacationTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VacationDay extends Model
{
    use HasFactory;

    protected $table = 'vacation_days';

    protected $guarded  = ['id'];

    protected $casts = [
        'vacation_pay_type' => VacationPayTypeEnum::class,
        'vacation_type' => VacationTypeEnum::class,
        'status' => VacationStatusEnum::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class)->where('status', EmployeeStatusEnum::ACTIVE);
    }
}
