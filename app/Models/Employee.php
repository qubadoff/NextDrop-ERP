<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'employees';

    protected $guarded = [];

    protected $casts = [
        'docs' => 'array',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
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

    public function EmployeeAttendance(): HasMany
    {
        return $this->hasMany(EmployeeAttendance::class, 'employee_id', 'id');
    }
}
