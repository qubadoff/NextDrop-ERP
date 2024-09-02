<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OwnerGroupResource\Pages;
use App\Models\OwnerGroup;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OwnerGroupResource extends Resource
{
    protected static ?string $model = OwnerGroup::class;

    protected static ?string $navigationGroup = 'Struktur';

    protected static ?string $navigationLabel = 'Əsas qrup';


    protected static ?string $label = 'Əsas qrup';

    protected static ?int $navigationSort = 1;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('name')->required()->label('Ad')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->label('Ad'),
                Tables\Columns\TextColumn::make('created_at')->label('Yaradıldı')->date(),
                Tables\Columns\TextColumn::make('updated_at')->label('Yeniləndi')->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOwnerGroups::route('/'),
            'create' => Pages\CreateOwnerGroup::route('/create'),
            'edit' => Pages\EditOwnerGroup::route('/{record}/edit'),
        ];
    }
}
