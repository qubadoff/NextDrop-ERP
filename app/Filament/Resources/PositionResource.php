<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PositionResource\Pages;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Position;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static ?string $navigationGroup = 'Struktur';

    protected static ?string $navigationLabel = 'Vəzifələr';


    protected static ?string $label = 'Vəzifə';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('department_id')
                        ->relationship('Department', 'name')
                        ->required()
                        ->label('Departament'),
                    TextInput::make('name')
                        ->required()
                        ->validationMessages([
                            'required' => 'Vəzifə adı mütləqdir.',
                            'unique' => 'Bu vəzifə adı artıq mövcuddur.',
                        ])
                        ->unique('positions', 'name', ignoreRecord: true)
                        ->label('Ad'),
                    TextInput::make('position_code')
                        ->required()
                        ->label('Vəzifə kodu')
                        ->validationMessages([
                            'required' => 'Vəzifə kodu mütləqdir.',
                            'unique' => 'Bu vəzifə kodu artıq mövcuddur.',
                        ])
                        ->unique('positions', 'position_code', ignoreRecord: true),
                    Select::make('state_unit')->options([
                        '1' => 'Tam ştat',
                        '2' => 'Yarım ştat',
                    ])->required()->label('Ştat vahidi'),
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
                Tables\Columns\TextColumn::make('Department.name')->label('Departament')->badge(),
                Tables\Columns\TextColumn::make('position_code')->label('Vəzifə Kodu')->badge(),
                Tables\Columns\TextColumn::make('state_unit')->label('Ştat vahidi')->badge(),
                Tables\Columns\TextColumn::make('created_at')->label('Yaradıldı')->date(),
                Tables\Columns\TextColumn::make('updated_at')->label('Yeniləndi')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->options(Department::all()->pluck('name', 'id'))
                    ->multiple()
                    ->searchable()
                    ->label('Departament'),
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
            'index' => Pages\ListPositions::route('/'),
            'create' => Pages\CreatePosition::route('/create'),
            'edit' => Pages\EditPosition::route('/{record}/edit'),
        ];
    }
}
