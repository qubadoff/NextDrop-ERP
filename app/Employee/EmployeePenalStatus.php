<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EmployeePenalStatus: int implements HasLabel, HasColor
{
    case APPROVED = 1;

    case PENDING = 2;

    case REJECTED = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::APPROVED => 'Təsdiqləndi',
            self::REJECTED => 'Ləğv edildi',
            default => 'Gözləmədədir',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            default => 'warning',
        };
    }
}
