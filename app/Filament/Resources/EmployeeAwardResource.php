<?php

namespace App\Filament\Resources;

use App\Employee\EmployeeAwardStatus;
use App\Employee\EmployeeAwardType;
use App\Employee\EmployeeStatusEnum;
use App\Filament\Resources\EmployeeAwardResource\Pages;
use App\Models\Employee;
use App\Models\EmployeeAward;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeAwardResource extends Resource
{
    protected static ?string $model = EmployeeAward::class;

    protected static ?string $navigationGroup = 'İşçi';

    protected static ?string $navigationLabel = 'Mükafatlar';


    protected static ?string $label = 'Mükafat';

    protected static ?int $navigationSort = 8;

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
                    DatePicker::make('date')->label('Tarix')->required(),
                    TextInput::make('award_amount')->label('Mükafat məbləği')->required()->suffix(' AZN')->numeric(),
                    TextInput::make('who_added')->label('Kim tərəfindən ?')->required(),
                    Select::make('award_type')->options([
                        EmployeeAwardType::ADD_SALARY->value => 'Maaşa əlavə',
                        EmployeeAwardType::NOW->value => 'Yerində',
                    ])->required()->label('Verilmə tipi'),
                    Select::make('status')->options([
                        EmployeeAwardStatus::PENDING->value => 'Gözləmədə',
                        EmployeeAwardStatus::APPROVED->value => 'Qəbul edildi',
                        EmployeeAwardStatus::REJECTED->value => 'Qəbul edilmədi',
                    ])->required()->label('Status'),
                    Textarea::make('reason')->label('Mükafat səbəbi')->required(),
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
                Tables\Columns\TextColumn::make('employee.name')->label('Ad')->searchable(),
                Tables\Columns\TextColumn::make('employee.surname')->label('Soyad')->searchable(),
                Tables\Columns\TextColumn::make('employee.id_pin_code')->label('Fin kod')->searchable(),
                Tables\Columns\TextColumn::make('award_amount')->label('Mükafat məbləği')->searchable()->money('AZN'),
                Tables\Columns\TextColumn::make('date')->label('Tarix')->date(),
                Tables\Columns\TextColumn::make('award_type')->label('Ödəniş tipi')->badge(),
                Tables\Columns\TextColumn::make('who_added')->label('Kim tərəfindən ?'),
                Tables\Columns\SelectColumn::make('status')->options([
                    EmployeeAwardStatus::PENDING->value => 'Gözləmədə',
                    EmployeeAwardStatus::APPROVED->value => 'Qəbul edildi',
                    EmployeeAwardStatus::REJECTED->value => 'Qəbul edilmədi',
                ])->label('Status'),
                Tables\Columns\TextColumn::make('created_at')->label('Əlavə olundu')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        EmployeeAwardStatus::PENDING->value => 'Gözləmədə',
                        EmployeeAwardStatus::APPROVED->value => 'Qəbul edildi',
                        EmployeeAwardStatus::REJECTED->value => 'Qəbul edilmədi',
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
            'index' => Pages\ListEmployeeAwards::route('/'),
            'create' => Pages\CreateEmployeeAward::route('/create'),
            'edit' => Pages\EditEmployeeAward::route('/{record}/edit'),
        ];
    }
}
