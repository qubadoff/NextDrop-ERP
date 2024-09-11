<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $guarded = [];

    protected $casts = [
        'docs' => 'array',
    ];

    public function education(): HasMany
    {
        return $this->hasMany(Education::class, 'employee_id', 'id');
    }

    public function language(): HasMany
    {
        return $this->hasMany(Language::class, 'employee_id', 'id');
    }

    public function programSkill(): HasMany
    {
        return $this->hasMany(ProgramSpeciality::class, 'employee_id', 'id');
    }

    public function certificate(): HasMany
    {
        return $this->hasMany(Certificate::class, 'employee_id', 'id');
    }

    public function workHours(): HasMany
    {
        return $this->hasMany(EmployeeWorkHour::class, 'employee_id', 'id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function workPalace(): HasMany
    {
        return $this->hasMany(WorkPalace::class, 'employee_id', 'id');
    }

    public function reference(): HasMany
    {
        return $this->hasMany(EmployeeReference::class, 'employee_id', 'id');
    }

    public function punishments(): HasMany
    {
        return $this->hasMany(DisciplinaryPunishment::class, 'employee_id', 'id');
    }
}
