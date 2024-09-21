<?php

namespace App\Vacation;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum VacationTypeEnum: int implements HasLabel, HasColor
{
    case OFFICIAL = 1;

    case UNOFFICIAL = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::OFFICIAL => 'Rəsmi',
            self::UNOFFICIAL => 'Qeyri Rəsmi',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::OFFICIAL => 'success',
            self::UNOFFICIAL => 'warning',
        };
    }
}