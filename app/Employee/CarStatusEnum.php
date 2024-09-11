<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CarStatusEnum: int implements HasColor, HasLabel
{
    case YES = 1;

    case NO = 0;

    public function getColor(): string
    {
        return match ($this) {
            self::YES => 'success',
            self::NO => 'danger',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::YES => 'Var',
            self::NO => 'Yoxdur',
        };
    }
}
