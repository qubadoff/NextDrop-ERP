<?php

namespace App\Vacation;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum VacationPayTypeEnum: int implements HasLabel, HasColor
{
    case PAID = 1;

    case UNPAID = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::PAID => 'Ödənişli',
            self::UNPAID => 'Ödənişsiz',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PAID => 'success',
            self::UNPAID => 'danger',
        };
    }
}
