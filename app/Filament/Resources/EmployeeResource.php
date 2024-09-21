<?php

namespace App\Filament\Resources;

use App\Employee\CarStatusEnum;
use App\Employee\DriverLicenseEnum;
use App\Employee\EducationStatusEnum;
use App\Employee\EmployeeSexEnum;
use App\Employee\EmployeeStateStatus;
use App\Employee\EmployeeWorkStatus;
use App\Employee\LanguageLevelEnum;
use App\Employee\LanguageStatusEnum;
use App\Employee\MaritalStatusEnum;
use App\Employee\MilitaryStatusEnum;
use App\Employee\ProgramSpecialityLevelEnum;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationGroup = 'İşçi';

    protected static ?string $navigationLabel = 'İşçilər';


    protected static ?string $label = 'İşçi';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('name')->required()->label('Adı'),
                    TextInput::make('surname')->required()->label('Soyadı'),
                    TextInput::make('father_name')->required()->label('Ata adı'),
                    TextInput::make('phone')->required()->label('Telefon'),
                    TextInput::make('email')->required()->email()->label('Email'),
                    DatePicker::make('birthday')->required()->label('Doğum tarixi'),
                    TextInput::make('id_number')->required()->label('Ş/V seriya nömrəsi'),
                    TextInput::make('id_pin_code')->required()->label('Ş/V fin kodu'),
                    TextInput::make('ssn')->required()->label('SSN'),
                    Select::make('sex')->options([
                        EmployeeSexEnum::MALE->value =>'Kişi',
                        EmployeeSexEnum::FEMALE->value => 'Qadın',
                        EmployeeSexEnum::OTHER->value => 'Digər',
                    ])->required()->label('Cins'),
                    TextInput::make('legal_address')->required()->label('Qeydiyyatda olduğu ünvan'),
                    TextInput::make('current_address')->required()->label('Yaşadığı ünvan'),
                    TextInput::make('nationality')->required()->label('Milliyət'),
                    Select::make('marital_status')->options([
                        MaritalStatusEnum::SINGLE->value => 'Subay',
                        MaritalStatusEnum::MARRIED->value => 'Evli',
                        MaritalStatusEnum::DIVORCED->value => 'Boşanmış',
                        MaritalStatusEnum::WIDOWED->value => 'Dul',
                        MaritalStatusEnum::OTHER->value => 'Dİgər',
                    ])->required()->label('Ailə vəziyyəti'),
                    Select::make('military_status')->options([
                        MilitaryStatusEnum::PASSED->value => 'Xidmətdə olub',
                        MilitaryStatusEnum::NOTPASSED->value => 'Xidmətdə olmayıb',
                    ])->required()->label('Hərbi xidmət'),
                ])->columns(5),

                Section::make([
                    Select::make('branch_id')->relationship('branch', 'name')->required()->label('Filial'),
                    Select::make('department_id')->relationship('department', 'name')->required()->label('Departament'),
                    Select::make('position_id')->relationship('position', 'name')->required()->label('Vəzifə'),
                    DatePicker::make('start_work_date')->required()->label('İşə başlama vaxtı'),
                    TextInput::make('gross_salary')->required()->label('Əmək haqqı ( GROSS )')->suffix(' AZN')->numeric(),
                    TextInput::make('net_salary')->required()->label('Əmək haqqı ( NET )')->suffix(' AZN')->numeric(),
                    TextInput::make('work_experience')->required()->label('İş stajı')->suffix(' İl')->numeric(),
                    Select::make('state')->options([
                        EmployeeStateStatus::FULL->value => 'Tam',
                        EmployeeStateStatus::PARTIAL->value => 'Yarım',
                    ])->required()->label('Ştatı'),
                    Select::make('work_status')->options([
                        EmployeeWorkStatus::OFFICIAL->value => 'Rəsmi',
                        EmployeeWorkStatus::UNOFFICIAL->value => 'Qeyri rəsmi',
                    ])->required()->label('İş tipi'),
                    TextInput::make('vacation')->required()->label('Məzuniyyət günlərinin sayı')->numeric(),
                ])->columns(4),

                Section::make([
                    Select::make('driver_license')->options([
                        DriverLicenseEnum::YES->value => 'Var',
                        DriverLicenseEnum::NO->value => 'Yoxdur',
                    ])->label('Sürücülük vəsiqəsi'),
                    TextInput::make('driver_license_number')->label('Sürücülük vəsiqəsinin nömrəsi'),
                    Select::make('car')->options([
                        CarStatusEnum::YES->value => 'Var',
                        CarStatusEnum::NO->value => 'Yoxdur',
                    ])->label('Avtomobili'),
                ])->columns(3),
                Section::make([
                    FileUpload::make('photo')->label('Şəkil')->image(),
                    FileUpload::make('id_photo_front')->label('Ş/V ön tərfi')->image(),
                    FileUpload::make('id_photo_back')->label('Ş/V arxa tərəfi')->image(),
                ])->columns(3),

                Section::make([
                    Repeater::make('education')
                        ->label('Təhsil')
                        ->relationship()
                        ->schema([
                            Select::make('name_id')->options([
                                EducationStatusEnum::HIGH->value => 'Ali təhsil',
                                EducationStatusEnum::SECONDARY->value => 'Natamam Orta',
                                EducationStatusEnum::COLLEGE->value => 'Orta',
                                EducationStatusEnum::MASTER->value => 'Magister',
                                EducationStatusEnum::DOCTORATE->value => 'Doktorantura',
                            ])->label('Təhsil səviyyəsi'),
                            TextInput::make('education_center')->label('Təhsil müəssisəsi'),
                            TextInput::make('speciality')->label('İxtisas'),
                            DatePicker::make('start_date')->label('Başlanğıc vaxtı'),
                            DatePicker::make('end_date')->label('Bitirmə vaxtı'),
                        ])->columns(3),
                ]),

                Section::make([
                    Repeater::make('language')
                        ->label('Dillər')
                        ->relationship()
                        ->schema([
                            Select::make('name_id')->options([
                                LanguageStatusEnum::AZ->value => 'Azərbaycan',
                                LanguageStatusEnum::EN->value => 'Ingilis',
                                LanguageStatusEnum::RU->value => 'Rus',
                                LanguageStatusEnum::DE->value => 'Alman',
                                LanguageStatusEnum::TR->value => 'Türk',
                            ])->label('Dil'),
                            Select::make('level_id')->options([
                                LanguageLevelEnum::A1->value => 'A1',
                                LanguageLevelEnum::A2->value => 'A2',
                                LanguageLevelEnum::B1->value => 'B1',
                                LanguageLevelEnum::B2->value => 'B2',
                                LanguageLevelEnum::C1->value => 'C1',
                                LanguageLevelEnum::C2->value => 'C2',
                            ])->label('Səviyyə')
                        ])->columns(),
                ]),

                Section::make([
                    Repeater::make('programSkill')
                        ->label('Proqram bilikləri')
                        ->relationship()
                        ->schema([
                            TextInput::make('name')->label('Proqramın adı'),
                            Select::make('level')->options([
                                ProgramSpecialityLevelEnum::SENIOR->value => 'Senior',
                                ProgramSpecialityLevelEnum::MIDDLE->value => 'Middle',
                                ProgramSpecialityLevelEnum::JUNIOR->value => 'Junior',
                            ])->label('Səviyyə'),
                        ])->columns(),
                ]),

                Section::make([
                    Repeater::make('certificate')
                        ->label('Sertifikatlar')
                        ->nullable()
                        ->relationship()
                        ->schema([
                            TextInput::make('name')->label('Sertifikatın adı'),
                            DatePicker::make('date')->label('Tarix')
                        ])->columns(),
                ]),

                Section::make([
                    Repeater::make('workHours')
                        ->label('İş vaxtları')
                        ->required()
                        ->relationship()
                        ->schema([
                            Select::make('weekday')->options([
                                1 => 'Bazar ertəsi',
                                2 => 'Çərşənbə axşamı',
                                3 => 'Çərşənbə günü',
                                4 => 'Cümə axşamı',
                                5 => 'Cümə',
                                6 => 'Şəmbə',
                                7 => 'Bazar',
                            ])->required()->label('Həftənin günü'),
                            TimePicker::make('start_time')->required()->label('Başlama vaxtı'),
                            TimePicker::make('end_time')->required()->label('Bitmə vaxtı'),
                        ])->columns(3),
                ]),

                Section::make([
                    Repeater::make('workPalace')
                        ->label('İş yerləri')
                        ->relationship()
                        ->schema([
                            TextInput::make('name')->label('Şirkətin adı'),
                            TextInput::make('position')->label('Vəzifə'),
                        ])->columns(),
                ]),

                Section::make([
                    Repeater::make('punishments')
                        ->label('İntizam cəzaları')
                        ->relationship()
                        ->schema([
                            FileUpload::make('doc')->label('Sənəd'),
                            Textarea::make('body')->label('Qeyd'),
                        ])->columns(),
                ]),

                Section::make([
                    Textarea::make('other_info')->label('Əlavə məlumat'),
                    FileUpload::make('docs')->multiple()->label('Sənədlər')
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
                Tables\Columns\TextColumn::make('name')->label('Ad')->searchable(),
                Tables\Columns\TextColumn::make('surname')->label('Soyad')->searchable(),
                Tables\Columns\TextColumn::make('father_name')->label('Ata adı')->searchable(),
                Tables\Columns\TextColumn::make('branch.name')->label('Filial')->searchable(),
                Tables\Columns\TextColumn::make('department.name')->label('Departament')->searchable(),
                Tables\Columns\TextColumn::make('position.name')->label('Vəzifə')->searchable(),
                Tables\Columns\TextColumn::make('gross_salary')->label('Gross ə/h')->money('AZN'),
                Tables\Columns\TextColumn::make('net_salary')->label('Net ə/h')->money('AZN'),
                Tables\Columns\TextColumn::make('start_work_date')->label('İşə başlama vaxtı')->date(),
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
                    Tables\Actions\ExportBulkAction::make(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
