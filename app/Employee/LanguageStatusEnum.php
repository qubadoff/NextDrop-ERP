<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum LanguageStatusEnum: int implements HasLabel, HasColor
{
    case AZ = 1;

    case EN = 2;

    case RU = 3;

    case TR = 4;

    case DE = 5;

    case FR = 6;

    case AR = 7;

    case IT = 8;

    case ES = 9;

    case PT = 10;

    case PL = 11;

    public function getLabel(): string
    {
        return match ($this) {
            LanguageStatusEnum::AZ => 'Azərbaycan dili',
            LanguageStatusEnum::EN => 'İngilis dili',
            LanguageStatusEnum::RU => 'Rus dili',
            LanguageStatusEnum::TR => 'Türk dili',
            LanguageStatusEnum::DE => 'Alman dili',
            LanguageStatusEnum::FR => 'Fransız dili',
            LanguageStatusEnum::AR => 'Ərəb dili',
            LanguageStatusEnum::IT => 'İtalyan dili',
            LanguageStatusEnum::ES => 'İspan dili',
            LanguageStatusEnum::PT => 'Portuqal dili',
            LanguageStatusEnum::PL => 'Polyak dili',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            LanguageStatusEnum::AZ, LanguageStatusEnum::FR, LanguageStatusEnum::DE, LanguageStatusEnum::RU, LanguageStatusEnum::EN, LanguageStatusEnum::TR, LanguageStatusEnum::AR, LanguageStatusEnum::IT, LanguageStatusEnum::ES, LanguageStatusEnum::PT, LanguageStatusEnum::PL => 'success',
        };
    }
}
