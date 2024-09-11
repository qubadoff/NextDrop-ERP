<?php

namespace App\Models;

use App\Employee\EmployeeAvansStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAvans extends Model
{
    use HasFactory;

    protected $table = 'employee_avans';

    protected $guarded = [];

    protected $casts = [
        'status' => EmployeeAvansStatus::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
