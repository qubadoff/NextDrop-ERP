<?php

namespace App\Observers;

use App\Models\VacationDay;
use App\Vacation\VacationStatusEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VacationOperationObserver
{
    /**
     * Handle the VacationDay "created" event.
     * @throws \Exception
     */
    public function created(VacationDay $vacationDay): void
    {
        if ($vacationDay->status === VacationStatusEnum::APPROVED) {

            $startDate = Carbon::parse($vacationDay->vacation_start_date);
            $endDate = Carbon::parse($vacationDay->vacation_end_date);

            // Calculate total days
            $totalDays = $startDate->diffInDays($endDate) + 1;

            // Check if the new vacation_all_days_count would be less than zero
            $newVacationAllDaysCount = $vacationDay->vacation_all_days_count - $totalDays;

            if ($newVacationAllDaysCount < 0) {
                // Trigger an alert (this could be a flash message, exception, or log)
                throw new \Exception('Vacation days cannot be negative!');
            }

            // Otherwise, proceed with updating and saving
            $vacationDay->vacation_all_days_count = $newVacationAllDaysCount;
            $vacationDay->save();
        }
    }

    /**
     * Handle the VacationDay "updated" event.
     */
    public function updated(VacationDay $vacationDay): void
    {
        if ($vacationDay->status === VacationStatusEnum::APPROVED) {

            // Calculate total vacation days between start and end dates
            $startDate = Carbon::parse($vacationDay->vacation_start_date);
            $endDate = Carbon::parse($vacationDay->vacation_end_date);
            $totalDays = $startDate->diffInDays($endDate) + 1;

            // Disable event dispatching temporarily to avoid recursion
            DB::transaction(function () use ($vacationDay, $totalDays) {
                $vacationDay->vacation_all_days_count -= $totalDays;

                // Save the model without triggering events
                $vacationDay->saveQuietly();
            });
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
