<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EmployeeAwardType: int implements HasLabel, HasColor
{
    case ADD_SALARY = 0;
    case NOW = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::ADD_SALARY => 'Maaşa əlavə',
            self::NOW => 'Yerində',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ADD_SALARY => 'success',
            self::NOW => 'warning',
        };
    }
}

