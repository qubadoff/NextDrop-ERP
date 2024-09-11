<?php

namespace App\Filament\Resources\EmployeeAwardResource\Pages;

use App\Filament\Resources\EmployeeAwardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeAwards extends ListRecords
{
    protected static string $resource = EmployeeAwardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
