<?php

namespace App\Observers;

use App\Models\VacationDay;
use Exception;
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
        DB::beginTransaction();

        try {
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
                    ->body('İşçinin məzuniyyət günlərinin sayı ' . $dayLimit . ' gündən artıq olmamalıdır !')
                    ->send();

                DB::rollBack();
                return;
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function updated(VacationDay $vacationDay): void
    {
        $employeeId = $vacationDay->employee_id;

        $dayLimit = DB::table('employee_vacation_day_options')
            ->where('employee_id', $employeeId)
            ->value('day_count');

        $totalVacationDays = DB::table('vacation_days')
            ->where('employee_id', $employeeId)
            ->where('id', '!=', $vacationDay->id) // Güncellenen satır hariç
            ->sum('vacation_day_count');

        $newTotal = $totalVacationDays + $vacationDay->vacation_day_count;

        if ($newTotal > $dayLimit) {
            Notification::make()
                ->title('Əməliyyat icra olunmadı !')
                ->danger()
                ->body('İşçinin məzuniyyət günlərinin sayı ' . $dayLimit - $totalVacationDays . ' - gündən artıq olmamalıdır !')
                ->send();

            DB::rollBack();
        }
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
