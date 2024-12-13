<?php

namespace App\Filament\Resources\VacationDayResource\Pages;

use App\Filament\Resources\VacationDayResource;
use App\Vacation\VacationStatusEnum;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\DB;

class EditVacationDay extends EditRecord
{
    protected static string $resource = VacationDayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * @throws Halt
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $employeeId = $data['employee_id'];

        if ($this->record->status == VacationStatusEnum::APPROVED) {
            Notification::make()
                ->title('Əməliyyat icra olunmadı!')
                ->danger()
                ->body('Təsdiqlənmiş məzuniyyət qeydini dəyişmək mümkün deyil!')
                ->send();

            $this->halt();
        }

        $dayLimit = DB::table('employee_vacation_day_options')
            ->where('employee_id', $employeeId)
            ->value('day_count');

        $totalVacationDays = DB::table('vacation_days')
            ->where('employee_id', $employeeId)
            ->where('id', '!=', $this->record->id)
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
