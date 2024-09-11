<?php

namespace App\Filament\Resources\EmployeePenalResource\Pages;

use App\Filament\Resources\EmployeePenalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeePenals extends ListRecords
{
    protected static string $resource = EmployeePenalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
