<?php

namespace App\Filament\Resources\EmployeeAwardResource\Pages;

use App\Filament\Resources\EmployeeAwardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeAward extends EditRecord
{
    protected static string $resource = EmployeeAwardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
