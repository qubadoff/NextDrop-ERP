<?php

namespace App\Filament\Resources\EmployeeAvansResource\Widgets;

use Filament\Widgets\ChartWidget;

class EmployeeAvansChart extends ChartWidget
{
    protected static ?string $heading = 'Ümumi Avanslar';

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
