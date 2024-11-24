<?php

namespace App\Filament\Resources\EmployeeAwardResource\Widgets;

use Filament\Widgets\ChartWidget;

class EmployeeAwardResource extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
