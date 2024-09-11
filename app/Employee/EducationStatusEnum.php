<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EducationStatusEnum: int implements HasLabel, HasColor
{
    case HIGH = 1;

    case SECONDARY = 2;

    case COLLEGE = 3;

    case MASTER = 4;

    case DOCTORATE = 5;

    public function getLabel(): string
    {
        return match ($this) {
            EducationStatusEnum::HIGH => 'Ali təhsil',
            EducationStatusEnum::MASTER => 'Magister',
            EducationStatusEnum::DOCTORATE => 'Doktorantura',
            EducationStatusEnum::SECONDARY => 'Natamam Orta',
            EducationStatusEnum::COLLEGE => 'Orta',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            EducationStatusEnum::HIGH, EducationStatusEnum::COLLEGE, EducationStatusEnum::SECONDARY, EducationStatusEnum::DOCTORATE, EducationStatusEnum::MASTER => 'secondary',
        };
    }
}
