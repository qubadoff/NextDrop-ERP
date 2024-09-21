<?php

namespace App\Filament\Resources;

use App\Employee\EmployeeAvansStatus;
use App\Filament\Resources\EmployeeAvansResource\Pages;
use App\Models\EmployeeAvans;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeAvansResource extends Resource
{
    protected static ?string $model = EmployeeAvans::class;

    protected static ?string $navigationGroup = 'İşçi';

    protected static ?string $navigationLabel = 'Avanslar';


    protected static ?string $label = 'Avans';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('employee_id')->relationship('employee', 'name')->required()->label('İşçi'),
                    DatePicker::make('date')->required()->label('Tarix'),
                    TextInput::make('amount')->required()->label('Məbləğ')->suffix(' AZN')->numeric(),
                    Textarea::make('reason')->label('Səbəb'),
                    Select::make('status')->options([
                        EmployeeAvansStatus::PENDING->value => 'Gözləmədə',
                        EmployeeAvansStatus::APPROVED->value => 'Qəbul edildi',
                        EmployeeAvansStatus::REJECTED->value => 'Ləğv edildi',
                    ])->required()->label('Status'),
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
                Tables\Columns\TextColumn::make('employee.name')->label('İşçi')->searchable(),
                Tables\Columns\TextColumn::make('amount')->label('Avans məbləği')->money('azn'),
                Tables\Columns\TextColumn::make('date')->label('Tarix')->date(),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
                Tables\Columns\TextColumn::make('created_at')->label('Əlavə olundu')->date(),
            ])->columns(3)
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
            'index' => Pages\ListEmployeeAvans::route('/'),
            'create' => Pages\CreateEmployeeAvans::route('/create'),
            'edit' => Pages\EditEmployeeAvans::route('/{record}/edit'),
        ];
    }
}
