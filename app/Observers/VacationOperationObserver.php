<?php

namespace App\Observers;

use App\Models\VacationDay;
use App\Vacation\VacationStatusEnum;
use Carbon\Carbon;

class VacationOperationObserver
{
    /**
     * Handle the VacationDay "created" event.
     */
    public function created(VacationDay $vacationDay): void
    {
        if ($vacationDay->status === VacationStatusEnum::APPROVED) {

            $startDate = Carbon::parse($vacationDay->vacation_start_date);

            $endDate = Carbon::parse($vacationDay->vacation_end_date);

            $totalDays = $startDate->diffInDays($endDate) + 1;

            $vacationDay->vacation_all_days_count -= $totalDays;

            $vacationDay->save();
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
