<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeLeaveOptionResource\Pages;
use App\Filament\Resources\EmployeeLeaveOptionResource\RelationManagers;
use App\Models\EmployeeLeaveOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeLeaveOptionResource extends Resource
{
    protected static ?string $model = EmployeeLeaveOption::class;

    protected static ?string $navigationLabel = 'Davamiyyət ayarları';

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $label = 'Ayar';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('time')->numeric()->required()->label('Vaxt')->suffix(' saat'),
                    Forms\Components\TextInput::make('penal')->numeric()->required()->label('Məbləğ')->suffix(' AZN'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('time')->label('Vaxt')->suffix(' saat'),
                Tables\Columns\TextColumn::make('penal')->label('Məbləğ')->money(' AZN'),
            ])->columns(2)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListEmployeeLeaveOptions::route('/'),
            'create' => Pages\CreateEmployeeLeaveOption::route('/create'),
            'edit' => Pages\EditEmployeeLeaveOption::route('/{record}/edit'),
        ];
    }
}
