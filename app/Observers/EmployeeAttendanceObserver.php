<?php

namespace App\Observers;

use App\Models\EmployeeAttendance;
use Carbon\Carbon;
use Exception;

class EmployeeAttendanceObserver
{
    /**
     * Handle the EmployeeAttendance "created" event.
     * @throws \Exception
     */
    public function created(EmployeeAttendance $employeeAttendance): void
    {
        $employeeIn = $employeeAttendance->employee_in;
        $employeeOut = $employeeAttendance->employee_out;

        if ($employeeIn && $employeeOut) {
            if (Carbon::parse($employeeOut)->lessThan(Carbon::parse($employeeIn))) {
                throw new Exception('Çıxış vaxtı giriş vaxtından balaca ola bilməz !');
            }

            $duration = Carbon::parse($employeeOut)->diffInMinutes(Carbon::parse($employeeIn));

            $employeeAttendance->update([
                'duration' => $duration * -1,
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
