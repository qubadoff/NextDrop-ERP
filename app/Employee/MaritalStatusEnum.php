<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MaritalStatusEnum: int implements HasLabel, HasColor
{
    case SINGLE = 1;

    case MARRIED = 2;

    case DIVORCED = 3;

    case WIDOWED = 4;

    case OTHER = 5;

    public function getLabel(): string
    {
        return match ($this) {
            self::SINGLE => 'Subay',
            self::MARRIED => 'Evli',
            self::DIVORCED => 'Boşanmış',
            self::WIDOWED => 'Dul',
            self::OTHER => 'Digər',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::SINGLE => 'success',
            self::MARRIED => 'danger',
            self::DIVORCED => 'warning',
            self::WIDOWED => 'info',
            self::OTHER => 'secondary',
        };
    }
}
