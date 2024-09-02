<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Models\Branch;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $navigationGroup = 'Struktur';

    protected static ?string $navigationLabel = 'Filial';


    protected static ?string $label = 'Filial';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('name')->required()->label('Ad'),
                    TextInput::make('voen')->required()->label('Vöen'),
                    TextInput::make('location')->required()->label('Ünvan'),
                    TextInput::make('field_of_action')->required()->label('Fəaliyyət sahəsi'),
                    Select::make('employee_count')->options([
                        '25' => '0-25',
                        '50' => '25-50',
                        '100' => '50-100',
                        '200' => '100-200',
                        '500' => '200-500',
                    ])->required()->label('İşçi sayı')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->label('Ad'),
                Tables\Columns\TextColumn::make('voen')->searchable()->label('Vöen'),
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
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
