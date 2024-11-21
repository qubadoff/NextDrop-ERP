<?php

namespace App\Filament\Resources\EmployeeLeaveOptionResource\Pages;

use App\Filament\Resources\EmployeeLeaveOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeLeaveOptions extends ListRecords
{
    protected static string $resource = EmployeeLeaveOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
