<?php

namespace App\Filament\Resources;

use App\Employee\EmployeePenalStatus;
use App\Employee\EmployeePenalTypeEnum;
use App\Filament\Resources\EmployeePenalResource\Pages;
use App\Models\EmployeePenal;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeePenalResource extends Resource
{
    protected static ?string $model = EmployeePenal::class;

    protected static ?string $navigationGroup = 'İşçi';

    protected static ?string $navigationLabel = 'Cərimələr';


    protected static ?string $label = 'Cərimə';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationIcon = 'heroicon-o-pencil';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('employee_id')->relationship('employee', 'name')->required()->label('İşçi'),
                    DatePicker::make('date')->label('Tarix')->required(),
                    TextInput::make('penal_amount')->label('Cərimə məbləği')->required()->suffix(' AZN')->numeric(),
                    TextInput::make('who_added')->label('Kim tərəfindən ?')->required(),
                    Select::make('penal_type')->options([
                        EmployeePenalTypeEnum::ONETIME->value => 'Birdəfəlik',
                        EmployeePenalTypeEnum::PART->value => 'Hissə-hissə',
                    ])->required()->label('Tutulma tipi'),
                    Select::make('status')->options([
                        EmployeePenalStatus::PENDING->value => 'Gözləmədə',
                        EmployeePenalStatus::APPROVED->value => 'Qəbul edildi',
                        EmployeePenalStatus::REJECTED->value => 'Qəbul edilmədi',
                    ])->required()->label('Status'),
                    Textarea::make('reason')->label('Cərimənin səbəbi')->required(),
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
                Tables\Columns\TextColumn::make('penal_amount')->label('Cərimə məbləği')->money('azn'),
                Tables\Columns\TextColumn::make('penal_type')->label('Tutulma tipi')->badge(),
                Tables\Columns\TextColumn::make('who_added')->label('Kim tərəfindən ?'),
                Tables\Columns\TextColumn::make('date')->label('Tarix')->date(),
                Tables\Columns\SelectColumn::make('status')->options([
                    EmployeePenalStatus::PENDING->value => 'Gözləmədə',
                    EmployeePenalStatus::APPROVED->value => 'Qəbul edildi',
                    EmployeePenalStatus::REJECTED->value => 'Qəbul edilmədi',
                ])->label('Status'),
                Tables\Columns\TextColumn::make('created_at')->label('Əlavə olundu')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        EmployeePenalStatus::PENDING->value => 'Gözləmədə',
                        EmployeePenalStatus::APPROVED->value => 'Qəbul edildi',
                        EmployeePenalStatus::REJECTED->value => 'Qəbul edilmədi',
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
            'index' => Pages\ListEmployeePenals::route('/'),
            'create' => Pages\CreateEmployeePenal::route('/create'),
            'edit' => Pages\EditEmployeePenal::route('/{record}/edit'),
        ];
    }
}
