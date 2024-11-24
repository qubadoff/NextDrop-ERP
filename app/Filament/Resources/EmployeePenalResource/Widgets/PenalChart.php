<?php

namespace App\Filament\Resources\EmployeePenalResource\Widgets;

use Filament\Widgets\ChartWidget;

class PenalChart extends ChartWidget
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
        return 'bar';
    }
}
