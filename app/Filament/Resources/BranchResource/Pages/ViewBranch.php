<?php

namespace App\Filament\Resources\BranchResource\Pages;

use App\Filament\Resources\BranchResource;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewBranch extends ViewRecord
{
    protected static string $resource = BranchResource::class;

    public function InfoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Əsas Bilgilər')
                    ->schema([
                        TextEntry::make('name')->label('Adı'),
                        TextEntry::make('voen')->label('VOEN'),
                        TextEntry::make('location')->label('Location'),
                        TextEntry::make('field_of_action')->label('Field of action'),
                        TextEntry::make('employee_count')->label('Employee count'),
                        ImageEntry::make('qr_code_path')->label('Qr kod')->width(100)->height(100),
                    ]),
            ]);
    }

}
