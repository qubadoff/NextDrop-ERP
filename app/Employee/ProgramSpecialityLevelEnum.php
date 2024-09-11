<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProgramSpecialityLevelEnum: int implements HasLabel, HasColor
{
    case SENIOR = 1;

    case MIDDLE = 2;

    case JUNIOR = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::SENIOR => 'Senior',
            self::MIDDLE => 'Middle',
            self::JUNIOR => 'Junior',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::SENIOR => 'success',
            self::MIDDLE => 'warning',
            self::JUNIOR => 'danger',
        };
    }
}
