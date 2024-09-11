<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EmployeeStateStatus: int implements HasLabel, HasColor
{
    case FULL = 1;

    case PARTIAL = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::FULL => 'Full',
            self::PARTIAL => 'Partial',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::FULL => 'success',
            self::PARTIAL => 'warning',
        };
    }
}
