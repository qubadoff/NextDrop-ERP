<?php

namespace App\Filament\Resources\VacationDayResource\Pages;

use App\Filament\Resources\VacationDayResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVacationDay extends CreateRecord
{
    protected static string $resource = VacationDayResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
