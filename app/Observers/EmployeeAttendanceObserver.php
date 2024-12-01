<?php

namespace App\Observers;

use App\Models\EmployeeAttendance;
use Carbon\Carbon;

class EmployeeAttendanceObserver
{
    /**
     * Handle the EmployeeAttendance "created" event.
     */
    public function created(EmployeeAttendance $employeeAttendance): void
    {
        $employeeIn = $employeeAttendance->employee_in;
        $employeeOut = $employeeAttendance->employee_out;

        if ($employeeIn && $employeeOut) {
            $duration = Carbon::parse($employeeOut)->diffInMinutes(Carbon::parse($employeeIn)) / 60;

            $employeeAttendance->update([
                'duration' => $duration,
            ]);
        }
    }

    /**
     * Handle the EmployeeAttendance "updated" event.
     */
    public function updated(EmployeeAttendance $employeeAttendance): void
    {
        //
    }

    /**
     * Handle the EmployeeAttendance "deleted" event.
     */
    public function deleted(EmployeeAttendance $employeeAttendance): void
    {
        //
    }

    /**
     * Handle the EmployeeAttendance "restored" event.
     */
    public function restored(EmployeeAttendance $employeeAttendance): void
    {
        //
    }

    /**
     * Handle the EmployeeAttendance "force deleted" event.
     */
    public function forceDeleted(EmployeeAttendance $employeeAttendance): void
    {
        //
    }
}
