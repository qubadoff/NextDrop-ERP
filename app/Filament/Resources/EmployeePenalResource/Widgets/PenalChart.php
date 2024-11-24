<?php

namespace App\Filament\Resources\EmployeePenalResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\EmployeePenal;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PenalChart extends ChartWidget
{
    protected static ?string $heading = 'Ümumi Cərimələr';

    // Filtreler için state belirle
    protected static ?array $filters = [
        'today' => 'Bugün',
        'week' => 'Bu Hafta',
        'month' => 'Bu Ay',
        'year' => 'Bu Yıl',
    ];

    protected function getData(): array
    {
        // Filtreyi al
        $filter = $this->filter ?? 'year'; // Varsayılan filtre 'year'

        // Tarih aralığını belirle
        switch ($filter) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                $interval = 'hour';
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                $interval = 'day';
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                $interval = 'day';
                break;
            case 'year':
            default:
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                $interval = 'month';
                break;
        }

        $data = Trend::model(EmployeePenal::class)
            ->dateColumn('created_at')
            ->between(start: $startDate, end: $endDate)
            ->interval($interval)
            ->sum('penal_amount');


        return [
            'datasets' => [
                [
                    'label' => 'Cərimə Miktarı',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bar grafiği
    }
}
