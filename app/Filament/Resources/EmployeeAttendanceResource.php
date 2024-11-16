<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeAttendanceResource\Pages;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeAttendanceResource extends Resource
{
    protected static ?string $model = EmployeeAttendance::class;

    protected static ?string $navigationLabel = 'Davamiyyət cədvəli';

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $label = 'Davamiyyət';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('branch_id')->label('Filial')->options(Branch::all()->pluck('name', 'id'))->required(),
                    Select::make('employee_id')->label('Əməkdaş')->options(Employee::all()->pluck('name', 'id'))->required(),
                    DateTimePicker::make('employee_in')->label('Giriş vaxtı')->required(),
                    DateTimePicker::make('employee_out')->label('Çıxış vaxtı')->required(),
                ])->columns()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('employee.name')->label('Ad')->searchable(),
                Tables\Columns\TextColumn::make('employee.surname')->label('Soyad')->searchable(),
                Tables\Columns\TextColumn::make('branch.name')->label('Filial')->searchable(),
                Tables\Columns\TextColumn::make('employee.position.name')->label('Vəzifə')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Tarix')->searchable()->date('Y-m-d'),
                Tables\Columns\TextColumn::make('employee_in')->label('Giriş vaxtı')->searchable()->time('H:i'),
                Tables\Columns\TextColumn::make('employee_out')->label('Çıxış vaxtı')->searchable()->time('H:i'),
                Tables\Columns\TextColumn::make('duration')
                    ->label('İşdə olduğu müddət (saat)')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? round($state / 60, 2) . ' saat' : '0 saat')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->formatStateUsing(fn ($state) => $state ? round($state / 60, 2) . ' saat' : '0 saat'),
                    ]),


            ])->defaultSort('created_at', 'desc')

            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label('Tarix')
                    ->date(),
            ])

            ->defaultSort('created_at', 'desc')

            ->filters([
                Tables\Filters\SelectFilter::make('employee_id')
                    ->options(Employee::all()->pluck('name', 'id'))
                    ->multiple()
                    ->searchable()
                    ->label('İşçi'),
                Tables\Filters\SelectFilter::make('branch_id')
                    ->options(Branch::all()->pluck('name', 'id'))
                    ->multiple()
                    ->searchable()
                    ->label('Filial'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultGroup('created_at');
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
            'index' => Pages\ListEmployeeAttendances::route('/'),
            //'create' => Pages\CreateEmployeeAttendance::route('/create'),
            //'edit' => Pages\EditEmployeeAttendance::route('/{record}/edit'),
        ];
    }
}
