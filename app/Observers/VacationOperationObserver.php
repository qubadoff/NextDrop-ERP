<?php

namespace App\Observers;

use App\Models\VacationDay;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class VacationOperationObserver
{
    /**
     * Handle the VacationDay "created" event.
     * @throws \Exception
     */
    public function created(VacationDay $vacationDay): void
    {
        $employeeId = $vacationDay->employee_id;

        $dayLimit = DB::table('employee_vacation_day_options')
            ->where('employee_id', $employeeId)
            ->value('day_count');

        $totalVacationDays = DB::table('vacation_days')
            ->where('employee_id', $employeeId)
            ->sum('vacation_day_count');

        if ($totalVacationDays > $dayLimit) {
            Notification::make()
                ->title('Əməliyyat icra olunmadı !')
                ->danger()
                ->body('İşçinin məzuniyyət günlərinin sayı ' . $dayLimit . ' dənədən artıq olmalıdır !')
                ->send();

            $vacationDay->delete();
        }

    }

    /**
     * Handle the VacationDay "updated" event.
     */
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
