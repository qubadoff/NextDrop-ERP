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

        // Tarih aralığını ve intervali belirle
        [$startDate, $endDate, $interval] = $this->getDateRangeAndInterval($filter);

        // Trend verilerini al
        $data = Trend::model(EmployeePenal::class)
            ->dateColumn('created_at')
            ->between(start: $startDate, end: $endDate)
            ->interval($interval)
            ->sum('penal_amount');

        // Grafiği döndür
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

    /**
     * Filtreye göre tarih aralığını ve intervali belirler.
     */
    private function getDateRangeAndInterval(string $filter): array
    {
        switch ($filter) {
            case 'today':
                return [
                    now()->startOfDay(),
                    now()->endOfDay(),
                    'hour', // Saatlik
                ];
            case 'week':
                return [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                    'day', // Günlük
                ];
            case 'month':
                return [
                    now()->startOfMonth(),
                    now()->endOfMonth(),
                    'day', // Günlük
                ];
            case 'year':
            default:
                return [
                    now()->startOfYear(),
                    now()->endOfYear(),
                    'month', // Aylık
                ];
        }
    }
}
