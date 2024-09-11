<?php

namespace App\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EmployeeLeaveStatusEnum: int implements HasLabel, HasColor
{
    case APPROVED = 1;

    case REJECTED = 2;

    case PENDING = 3;

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
