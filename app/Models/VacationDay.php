<?php

namespace App\Models;

use App\Vacation\VacationPayTypeEnum;
use App\Vacation\VacationStatusEnum;
use App\Vacation\VacationTypeEnum;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vacationDay) {
            $employeeId = $vacationDay->employee_id;

            $dayLimit = DB::table('employee_vacation_day_options')
                ->where('employee_id', $employeeId)
                ->value('day_count');

            $totalVacationDays = DB::table('vacation_days')
                ->where('employee_id', $employeeId)
                ->sum('vacation_day_count');

            if ($totalVacationDays + $vacationDay->vacation_day_count > $dayLimit) {
                Notification::make()
                    ->title('Əməliyyat icra olunmadı !')
                    ->danger()
                    ->body('İşçinin məzuniyyət günlərinin sayı ' . $dayLimit . ' gündən artıq olmamalıdır !')
                    ->send();

                return false;
            }
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
