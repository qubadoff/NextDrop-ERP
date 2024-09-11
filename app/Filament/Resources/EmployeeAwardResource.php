<?php

namespace App\Filament\Resources;

use App\Employee\EmployeeAwardStatus;
use App\Employee\EmployeeAwardType;
use App\Employee\EmployeePenalStatus;
use App\Employee\EmployeePenalTypeEnum;
use App\Filament\Resources\EmployeeAwardResource\Pages;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('employee_id')->relationship('employee', 'name')->required()->label('İşçi'),
                    DatePicker::make('date')->label('Tarix')->required(),
                    TextInput::make('award_amount')->label('Mükafat məbləği')->required()->suffix(' AZN')->numeric(),
                    Textarea::make('reason')->label('Mükafat səbəbəi')->required(),
                    Select::make('award_type')->options([
                        EmployeeAwardType::ADD_SALARY->value => 'Maaşa əlavə',
                        EmployeeAwardType::NOW->value => 'Yerində',
                    ])->required()->label('Verilmə tipi'),
                    Select::make('status')->options([
                        EmployeeAwardStatus::PENDING->value => 'Gözləmədə',
                        EmployeeAwardStatus::APPROVED->value => 'Qəbul edildi',
                        EmployeeAwardStatus::REJECTED->value => 'Qəbul edilmədi',
                    ])->required()->label('Status'),
                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('employee.name')->label('İşçi')->searchable(),
                Tables\Columns\TextColumn::make('award_amount')->label('Mükafat məbləği')->searchable()->money('AZN'),
                Tables\Columns\TextColumn::make('date')->label('Tarix')->date(),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
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
            'index' => Pages\ListEmployeeAwards::route('/'),
            'create' => Pages\CreateEmployeeAward::route('/create'),
            'edit' => Pages\EditEmployeeAward::route('/{record}/edit'),
        ];
    }
}
