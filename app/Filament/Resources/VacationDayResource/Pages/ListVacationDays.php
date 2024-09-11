<?php

namespace App\Filament\Resources\VacationDayResource\Pages;

use App\Filament\Resources\VacationDayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVacationDays extends ListRecords
{
    protected static string $resource = VacationDayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
