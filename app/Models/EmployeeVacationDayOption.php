<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeVacationDayOption extends Model
{
    protected $table = 'employee_vacation_day_options';

    protected $guarded = ['id'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
