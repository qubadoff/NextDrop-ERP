<?php

namespace App\Filament\Resources\OwnerGroupResource\Pages;

use App\Filament\Resources\OwnerGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOwnerGroups extends ListRecords
{
    protected static string $resource = OwnerGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
