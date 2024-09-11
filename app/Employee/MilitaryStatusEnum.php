<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MilitaryStatusEnum: int implements HasLabel, HasColor
{
    case PASSED = 1;

    case NOTPASSED = 2;


    public function getLabel(): string
    {
        return match ($this) {
            self::PASSED => 'Hərbi xidmət keçib',
            self::NOTPASSED => 'Xidmətdə Olmayıb',
        };
    }


    public function getColor(): string
    {
        return match ($this) {
            self::PASSED => 'success',
            self::NOTPASSED => 'danger',
        };
    }
}
