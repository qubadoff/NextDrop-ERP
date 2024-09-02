<?php

namespace App\Filament\Resources\SecondGroupResource\Pages;

use App\Filament\Resources\SecondGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSecondGroup extends EditRecord
{
    protected static string $resource = SecondGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
