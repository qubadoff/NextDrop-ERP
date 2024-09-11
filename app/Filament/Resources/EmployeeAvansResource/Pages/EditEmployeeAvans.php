<?php

namespace App\Filament\Resources\EmployeeAvansResource\Pages;

use App\Filament\Resources\EmployeeAvansResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeAvans extends EditRecord
{
    protected static string $resource = EmployeeAvansResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
