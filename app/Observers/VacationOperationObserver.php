<?php

namespace App\Observers;

use App\Models\VacationDay;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class VacationOperationObserver
{
    /**
     * Handle the VacationDay "creating" event.
     */
    public function creating(VacationDay $vacationDay): bool
    {
        $employeeId = $vacationDay->employee_id;

        $dayLimit = DB::table('employee_vacation_day_options')
            ->where('employee_id', $employeeId)
            ->value('day_count');

        $totalVacationDays = DB::table('vacation_days')
            ->where('employee_id', $employeeId)
            ->sum('vacation_day_count');

        if ($totalVacationDays + $vacationDay->vacation_day_count > $dayLimit) {
            Notification::make()
                ->title('Əməliyyat icra olunmadı!')
                ->danger()
                ->body('İşçinin məzuniyyət günlərinin sayı ' . $dayLimit . ' gündən artıq olmamalıdır!')
                ->send();

            return false;
        }

        return true;
    }

    public function updated(VacationDay $vacationDay): void
    {
        //
    }


    /**
     * Handle the VacationDay "deleted" event.
     */
    public function deleted(VacationDay $vacationDay): void
    {
        //
    }

    /**
     * Handle the VacationDay "restored" event.
     */
    public function restored(VacationDay $vacationDay): void
    {
        //
    }

    /**
     * Handle the VacationDay "force deleted" event.
     */
    public function forceDeleted(VacationDay $vacationDay): void
    {
        //
    }
}
