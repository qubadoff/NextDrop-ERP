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

        // employee_in ve employee_out dolu mu kontrol et
        if ($employeeIn && $employeeOut) {
            // Giriş ve çıkış zamanlarının sırasını kontrol et
            if (Carbon::parse($employeeOut)->lessThan(Carbon::parse($employeeIn))) {
                // Negatif süreyi önlemek için işlem yapılmaz ve hata gösterilir
                throw new Exception('Çıkış zamanı giriş zamanından önce olamaz!');
            }

            // Dakika cinsinden süreyi hesapla
            $duration = Carbon::parse($employeeOut)->diffInMinutes(Carbon::parse($employeeIn));

            // Duration değerini dakika olarak kaydet
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
