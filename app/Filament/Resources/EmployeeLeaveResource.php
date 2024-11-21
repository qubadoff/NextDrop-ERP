<?php

namespace App\Filament\Resources;

use App\Employee\EmployeeLeaveStatusEnum;
use App\Filament\Resources\EmployeeLeaveResource\Pages;
use App\Models\EmployeeLeave;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeLeaveResource extends Resource
{
    protected static ?string $model = EmployeeLeave::class;

    protected static ?string $navigationGroup = 'İşçi';

    protected static ?string $navigationLabel = 'İcazələr';


    protected static ?string $label = 'İcazə';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('employee_id')
                        ->relationship('employee', 'name')
                        ->required()
                        ->label('İşçi'),
                    DateTimePicker::make('start_date')->required()->label('Başlanğıc tarixi'),
                    DateTimePicker::make('end_date')->required()->label('Bitiş tarixi'),
                    Textarea::make('reason')->required()->label('Səbəb'),
                    Select::make('status')->options([
                        EmployeeLeaveStatusEnum::PENDING->value => 'Gözləmədə',
                        EmployeeLeaveStatusEnum::APPROVED->value => 'Təsdiqləndi',
                        EmployeeLeaveStatusEnum::REJECTED->value => 'Ləğv edildi',
                    ])->required()->label('Status'),
                ])->columns(3)
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
                Tables\Columns\TextColumn::make('start_date')->label('Başlanış tarixi')->dateTime(),
                Tables\Columns\TextColumn::make('end_date')->label('Bitiş tarixi')->dateTime(),
                Tables\Columns\SelectColumn::make('status')->options([
                    EmployeeLeaveStatusEnum::PENDING->value => 'Gözləmədə',
                    EmployeeLeaveStatusEnum::APPROVED->value => 'Təsdiqləndi',
                    EmployeeLeaveStatusEnum::REJECTED->value => 'Ləğv edildi',
                ])->label('Status'),
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
            'index' => Pages\ListEmployeeLeaves::route('/'),
            'create' => Pages\CreateEmployeeLeave::route('/create'),
            'edit' => Pages\EditEmployeeLeave::route('/{record}/edit'),
        ];
    }
}
