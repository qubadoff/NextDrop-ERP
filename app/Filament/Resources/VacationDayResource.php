<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VacationDayResource\Pages;
use App\Models\VacationDay;
use App\Vacation\VacationPayTypeEnum;
use App\Vacation\VacationTypeEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VacationDayResource extends Resource
{
    protected static ?string $model = VacationDay::class;

    protected static ?string $navigationGroup = 'İşçi';

    protected static ?string $navigationLabel = 'Məzuniyyət günləri';


    protected static ?string $label = 'Məzuniyyət';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('employee_id')->relationship('employee', 'name')
                        ->required()->label('İşçi'),
                    DatePicker::make('vacation_start_date')->required()->label('Məzuniyyətin başlama vaxtı'),
                    DatePicker::make('vacation_end_date')->required()->label('Məzuniyyətin bitmə vaxtı'),
                    TextInput::make('amount')->label('Məbləğ')->suffix(' AZN'),
                    Select::make('vacation_pay_type')->options([
                        VacationPayTypeEnum::PAID->value => 'Ödənişli',
                        VacationPayTypeEnum::UNPAID->value => 'Ödənişsiz',
                    ])->required()->label('Növü'),
                    Select::make('vacation_type')->options([
                        VacationTypeEnum::OFFICIAL->value => 'Rəsmi',
                        VacationTypeEnum::UNOFFICIAL->value => 'Qeyri rəsmi',
                    ])->required()->label('Məzuniyyət tipi'),
                ])->columns(4)
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
                Tables\Columns\TextColumn::make('employee.vacation')->label('Məzuniyyət günlərinin sayı')->badge(),
                Tables\Columns\TextColumn::make('vacation_start_date')->label('Başlama vaxtı')->date(),
                Tables\Columns\TextColumn::make('vacation_end_date')->label('Bitmə vaxtı')->date(),
                Tables\Columns\TextColumn::make('vacation_pay_type')->label('Növü')->badge(),
                Tables\Columns\TextColumn::make('vacation_type')->label('Məzuniyyət tipi')->badge(),
                Tables\Columns\TextColumn::make('created_at')->label('Əlavə olundu')->date(),
                Tables\Columns\TextColumn::make('updated_at')->label('Yeniləndi')->date(),
            ])
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
            'index' => Pages\ListVacationDays::route('/'),
            'create' => Pages\CreateVacationDay::route('/create'),
            'edit' => Pages\EditVacationDay::route('/{record}/edit'),
        ];
    }
}
