<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SecondGroupResource\Pages;
use App\Models\SecondGroup;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SecondGroupResource extends Resource
{
    protected static ?string $model = SecondGroup::class;

    protected static ?string $navigationGroup = 'Struktur';

    protected static ?string $navigationLabel = 'Alt qrup';


    protected static ?string $label = 'Alt qrup';

    protected static ?int $navigationSort = 3;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('owner_group_id')->relationship('ownerGroup', 'name')->required()->label('Əsas qrup'),
                    TextInput::make('name')->required()->label('Ad'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('№')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('name')->searchable()->label('Ad'),
                Tables\Columns\TextColumn::make('ownerGroup.name')->label('Əsas qrup')->badge(),
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
            'index' => Pages\ListSecondGroups::route('/'),
            'create' => Pages\CreateSecondGroup::route('/create'),
            'edit' => Pages\EditSecondGroup::route('/{record}/edit'),
        ];
    }
}
