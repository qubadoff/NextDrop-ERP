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
     * @throws Exception
     */
    public function created(VacationDay $vacationDay): void
    {
        //
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
