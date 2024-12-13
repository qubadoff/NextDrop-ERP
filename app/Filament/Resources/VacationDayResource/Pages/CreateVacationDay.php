<?php

namespace App\Filament\Resources\VacationDayResource\Pages;

use App\Filament\Resources\VacationDayResource;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\DB;

class CreateVacationDay extends CreateRecord
{
    protected static string $resource = VacationDayResource::class;

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

        $startDate = Carbon::parse($data['vacation_start_date']);
        $endDate = Carbon::parse($data['vacation_end_date']);
        $calculatedDays = $startDate->diffInDays($endDate) + 1;

        if ($data['vacation_day_count'] != $calculatedDays) {
            Notification::make()
                ->title('Əməliyyat icra olunmadı!')
                ->danger()
                ->body('Məzuniyyət günlərinin sayı seçilən tarixlərə uyğun deyil. Hesaplanan gün sayı: ' . $calculatedDays)
                ->send();

            $this->halt();
        }

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
