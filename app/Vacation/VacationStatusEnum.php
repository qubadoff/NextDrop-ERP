<?php

namespace App\Vacation;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum VacationStatusEnum: int implements HasLabel, HasColor
{
    case APPROVED = 1;
    case CANCELED = 2;
    case PENDING = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::APPROVED => 'Təsdiqləndi',
            self::PENDING => 'Gözləmədə',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::APPROVED => 'success',
            self::CANCELED => 'danger',
            self::PENDING => 'warning',
        };
    }
}
