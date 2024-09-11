<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum LanguageLevelEnum: int implements HasLabel, HasColor
{
    case A1 = 1;

    case A2 = 2;

    case B1 = 3;

    case B2 = 4;

    case C1 = 5;

    case C2 = 6;

    public function getLabel(): string
    {
        return match ($this) {
            self::A1 => 'A1',
            self::A2 => 'A2',
            self::B1 => 'B1',
            self::B2 => 'B2',
            self::C1 => 'C1',
            self::C2 => 'C2',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::A1, self::A2 => 'success',
            self::B1, self::B2 => 'warning',
            self::C1, self::C2 => 'danger',
        };
    }
}
