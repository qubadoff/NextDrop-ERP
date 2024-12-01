<?php

namespace App\Filament\Resources\VacationDayResource\Pages;

use App\Filament\Resources\VacationDayResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateVacationDay extends CreateRecord
{
    protected static string $resource = VacationDayResource::class;

    protected function beforeCreate(array $data): void
    {
        $employeeId = $data['employee_id'];

        // Employee'nin vacation day limitini al
        $dayLimit = DB::table('employee_vacation_day_options')
            ->where('employee_id', $employeeId)
            ->value('day_count');

        // Mevcut alınan vacation days toplamını hesapla
        $totalVacationDays = DB::table('vacation_days')
            ->where('employee_id', $employeeId)
            ->sum('vacation_day_count');

        // Yeni tatil günüyle toplamı kontrol et
        if ($totalVacationDays + $data['vacation_day_count'] > $dayLimit) {
            Notification::make()
                ->title('Əməliyyat icra olunmadı !')
                ->danger()
                ->body('İşçinin məzuniyyət günlərinin sayı ' . $dayLimit . ' gündən artıq olmamalıdır !')
                ->send();

            // İşlemi durdur
            $this->halt();
        }
    }

}
