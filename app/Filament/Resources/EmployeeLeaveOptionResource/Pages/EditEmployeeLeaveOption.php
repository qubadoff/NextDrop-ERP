<?php

namespace App\Filament\Resources\EmployeeLeaveOptionResource\Pages;

use App\Filament\Resources\EmployeeLeaveOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeLeaveOption extends EditRecord
{
    protected static string $resource = EmployeeLeaveOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
