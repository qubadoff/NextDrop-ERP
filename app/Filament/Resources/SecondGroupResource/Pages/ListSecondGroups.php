<?php

namespace App\Filament\Resources\SecondGroupResource\Pages;

use App\Filament\Resources\SecondGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSecondGroups extends ListRecords
{
    protected static string $resource = SecondGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
