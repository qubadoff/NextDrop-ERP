<?php

namespace App\Filament\Resources;

use App\Employee\EmployeeAvansStatus;
use App\Employee\EmployeeStatusEnum;
use App\Filament\Resources\EmployeeAvansResource\Pages;
use App\Models\Employee;
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

    protected static ?string $navigationIcon = 'heroicon-o-pencil';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('employee_id')
                        ->options(
                            Employee::where('status', EmployeeStatusEnum::ACTIVE)
                                ->get()
                                ->mapWithKeys(function ($person) {
                                    return [$person->id => $person->name . ' ' . $person->surname . ' ' . $person->id_pin_code];
                                })
                        )
                        ->required()
                        ->label('Əməkdaş'),
                    DatePicker::make('date')->required()->label('Tarix'),
                    TextInput::make('amount')->required()->label('Məbləğ')->suffix(' AZN')->numeric(),
                    Select::make('status')->options([
                        EmployeeAvansStatus::PENDING->value => 'Gözləmədə',
                        EmployeeAvansStatus::APPROVED->value => 'Qəbul edildi',
                        EmployeeAvansStatus::REJECTED->value => 'Ləğv edildi',
                    ])->required()->label('Status'),
                    Textarea::make('reason')->label('Səbəb'),
                ])->columns(3),
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
                Tables\Columns\TextColumn::make('employee.name')->label('Ad')->searchable(),
                Tables\Columns\TextColumn::make('employee.surname')->label('Soyad')->searchable(),
                Tables\Columns\TextColumn::make('employee.id_pin_code')->label('Fin kod')->searchable(),
                Tables\Columns\TextColumn::make('amount')->label('Avans məbləği')->money('azn'),
                Tables\Columns\TextColumn::make('date')->label('Tarix')->date(),
                Tables\Columns\SelectColumn::make('status')->options([
                    EmployeeAvansStatus::PENDING->value => 'Gözləmədə',
                    EmployeeAvansStatus::APPROVED->value => 'Qəbul edildi',
                    EmployeeAvansStatus::REJECTED->value => 'Ləğv edildi',
                ])->label('Status'),
                Tables\Columns\TextColumn::make('created_at')->label('Əlavə olundu')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        EmployeeAvansStatus::PENDING->value => 'Gözləmədə',
                        EmployeeAvansStatus::APPROVED->value => 'Qəbul edildi',
                        EmployeeAvansStatus::REJECTED->value => 'Ləğv edildi',
                    ])
                    ->multiple()
                    ->searchable()
                    ->label('Status'),
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
