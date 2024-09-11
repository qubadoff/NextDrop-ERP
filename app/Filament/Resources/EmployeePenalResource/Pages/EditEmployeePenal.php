<?php

namespace App\Filament\Resources\EmployeePenalResource\Pages;

use App\Filament\Resources\EmployeePenalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeePenal extends EditRecord
{
    protected static string $resource = EmployeePenalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
