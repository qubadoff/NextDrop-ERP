<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EmployeeSexEnum: int implements HasLabel, HasColor
{
    case MALE = 1;

    case FEMALE = 2;

    case OTHER = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::MALE => 'Kişi',
            self::FEMALE => 'Qadın',
            self::OTHER => 'Digər',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::MALE => 'success',
            self::FEMALE => 'danger',
            self::OTHER => 'warning',
        };
    }
}
