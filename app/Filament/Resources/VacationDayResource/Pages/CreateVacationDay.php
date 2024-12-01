<?php

namespace App\Filament\Resources\VacationDayResource\Pages;

use App\Filament\Resources\VacationDayResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;

class CreateVacationDay extends CreateRecord
{
    protected static string $resource = VacationDayResource::class;

//    protected function getRedirectUrl(): string
//    {
//        return $this->getResource()::getUrl('index');
//    }

    /**
     * @throws Halt
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $employeeId = $data['employee_id'];

        $dayLimit = DB::table('employee_vacation_day_options')
            ->where('employee_id', $employeeId)
            ->value('day_count');

        $totalVacationDays = DB::table('vacation_days')
            ->where('employee_id', $employeeId)
            ->sum('vacation_day_count');

        if ($totalVacationDays + $data['vacation_day_count'] > $dayLimit) {
            Notification::make()
                ->title('Əməliyyat icra olunmadı!')
                ->danger()
                ->body('İşçinin məzuniyyət günlərinin sayı ' . ($dayLimit - $totalVacationDays) . ' gündən artıq olmamalıdır!')
                ->send();

            $this->halt();
        }

        return $data;
    }


}
