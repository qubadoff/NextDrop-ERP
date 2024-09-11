<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EmployeePenalTypeEnum: int implements HasLabel, HasColor
{
    case ONETIME = 0;
    case PART = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::ONETIME => 'Birdəfəlik',
            self::PART => 'Hissə-hissə',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ONETIME => 'success',
            self::PART => 'warning',
        };
    }
}
